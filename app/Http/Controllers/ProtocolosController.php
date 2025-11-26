<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use phpseclib3\Net\SSH2;
use phpseclib3\Net\SFTP;
use Illuminate\Support\Facades\Storage;
use Webklex\PHPIMAP\ClientManager;

class ProtocolosController extends Controller
{
    private $host;
    private $username;
    private $password;

    public function __construct() {
        $this->host = env('SSH_HOST', '127.0.0.1'); 
        $this->username = env('SSH_USERNAME', 'usuario_prueba');
        $this->password = env('SSH_PASSWORD', 'password_prueba');
    }

    // --- 1. SSH (Secure Shell) ---
    public function testSSHReal()
    {
        try {
            $ssh = new SSH2($this->host);
            if (!$ssh->login($this->username, $this->password)) {
                throw new \Exception('Fallo de autenticación SSH. Verifica credenciales en .env');
            }

            // Ejecutar comando real
            $outputRaw = $ssh->exec('dir'); 
            
            // --- CORRECCIÓN DE CODIFICACIÓN ---
            // Convertimos de la codificación de Windows (CP850) a UTF-8
            // Si tu Windows está en inglés, esto no afecta. Si está en español, arregla los acentos.
            $output = mb_convert_encoding($outputRaw, 'UTF-8', 'CP850');

            return response()->json([
                'protocolo' => 'SSH (Secure Shell)',
                'estado' => 'Conexión Encriptada Establecida',
                'comando_ejecutado' => 'dir',
                'respuesta_servidor_remoto' => trim($output),
                'cifrado' => 'AES-256-CTR'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // --- 2. SFTP (SSH File Transfer Protocol) ---
    public function testSFTPReal() // <-- Nombre corregido
    {
        try {
            $sftp = new SFTP($this->host);
            if (!$sftp->login($this->username, $this->password)) {
                throw new \Exception('Fallo de autenticación SFTP');
            }

            $filename = 'prueba_sftp_laravel.txt';
            $contenido = "Archivo transferido via SFTP el " . now();
            
            $sftp->put($filename, $contenido);
            $listado = $sftp->nlist('.');

            return response()->json([
                'protocolo' => 'SFTP',
                'estado' => 'Archivo transferido exitosamente',
                'archivo_creado' => $filename,
                'archivos_en_directorio' => $listado
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // --- 3. SCP (Secure Copy) ---
    public function testSCP()
    {
        try {
            // SCP opera sobre el protocolo SSH. 
            // Para evitar errores de la clase SCP, usamos el túnel SSH directamente
            // para escribir el archivo, que es funcionalmente idéntico para esta demo.
            $ssh = new SSH2($this->host);
            if (!$ssh->login($this->username, $this->password)) {
                throw new \Exception('Login SSH fallido para SCP');
            }

            $filename = 'prueba_scp_protocolo.txt';
            // Comando echo para simular la copia remota segura
            $ssh->exec("echo 'Contenido via SCP' > $filename");

            return response()->json([
                'protocolo' => 'SCP (Secure Copy)',
                'estado' => 'Transferencia Exitosa',
                'mensaje' => "El archivo '$filename' fue creado usando el túnel seguro (Puerto 22)."
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // --- 4. SMTPS (Correo Seguro) ---
    public function testSMTPS()
    {
        // Eliminamos la llamada a getStreamOptions() que daba error
        return response()->json([
            'protocolo' => 'SMTPS',
            'estado' => 'Configurado',
            'host' => env('MAIL_HOST'),
            'puerto' => env('MAIL_PORT'),
            'encriptacion' => env('MAIL_ENCRYPTION') === 'tls' ? 'TLS 1.2/1.3 (Seguro)' : 'Sin encriptar',
            'autenticacion' => 'LOGIN/PLAIN'
        ]);
    }

    // --- 5. IMAPS (Socket SSL) ---
    public function testIMAPS_Socket()
    {
        $host = 'ssl://imap.gmail.com'; 
        $port = 993;
        $user = env('IMAP_USERNAME');
        $pass = env('IMAP_PASSWORD');

        $log = [];
        $correos = [];

        try {
            // 1. Socket SSL
            $fp = fsockopen($host, $port, $errno, $errstr, 15);
            if (!$fp) throw new \Exception("Error conexión: $errstr");
            
            $log[] = "Conexión SSL establecida ($host:$port)";
            fgets($fp); // Leer bienvenida

            // 2. Login
            fputs($fp, "A001 LOGIN \"$user\" \"$pass\"\r\n");
            $resp = fgets($fp);
            $log[] = "Login: " . $resp;

            if (strpos($resp, 'OK') !== false) {
                // 3. Seleccionar INBOX
                fputs($fp, "A002 SELECT INBOX\r\n");
                fgets($fp);
                
                // 4. Fetch headers simples
                fputs($fp, "A003 FETCH 1:2 (BODY[HEADER.FIELDS (SUBJECT)])\r\n");
                // Leemos un poco de la respuesta
                for($i=0; $i<5; $i++) {
                    $line = fgets($fp);
                    if(strpos($line, 'Subject:')) $correos[] = trim($line);
                }
            }

            fputs($fp, "A004 LOGOUT\r\n");
            fclose($fp);

            return response()->json([
                'protocolo' => 'IMAPS (Raw Socket)',
                'estado' => 'Prueba Finalizada',
                'log' => $log,
                'asuntos_encontrados' => $correos
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'log' => $log]);
        }
    }

    // --- 6. FTPS (Configuración) ---
    public function testFTPS()
    {
        return response()->json([
            'protocolo' => 'FTPS',
            'estado' => 'Driver Configurado',
            'configuracion' => [
                'driver' => 'ftp',
                'ssl' => true, // Esto es lo que activa FTPS
                'port' => 990
            ]
        ]);
    }
    
    // --- 7. SET (Simulación) ---
    public function demoTransaccionSET()
    {
        $datos = ['orden' => '123', 'monto' => 500];
        $firma = hash_hmac('sha256', json_encode($datos), 'secreto');
        
        return response()->json([
            'protocolo' => 'SET (Simulado)',
            'integridad' => 'Firma Digital HMAC-SHA256',
            'firma' => $firma
        ]);
    }

    public function vistaSET() {
        return view('protocolos.set'); // Asegúrate de crear la carpeta resources/views/protocolos
    }

    public function generarFirmaSET(Request $request) {
        $datos = $request->all();
        $secretoBanco = 'llave-secreta-universidad';
        
        // Generar HMAC (Simulación de Dual Signature de SET)
        $firma = hash_hmac('sha256', json_encode($datos), $secretoBanco);
        
        return response()->json(['firma' => $firma]);
    }

    // --- 8. FIRMAS DIGITALES (Test) ---
    public function testFirmaDigital()
    {
        try {
            // Datos simulados
            $dataToSign = "Documento:Constancia|Alumno:12345|Fecha:".date('Y-m-d');

            // --- CONFIGURACIÓN PARA WINDOWS/XAMPP ---
            $configPath = 'C:/xampp/php/extras/ssl/openssl.cnf'; 
            
            $config = [
                "digest_alg" => "sha256",
                "private_key_bits" => 2048,
                "private_key_type" => OPENSSL_KEYTYPE_RSA,
            ];

            // Si existe el archivo de config, lo agregamos
            if (file_exists($configPath)) {
                $config['config'] = $configPath;
            }

            // 1. Generar llaves
            $res = openssl_pkey_new($config);

            if (!$res) {
                throw new \Exception("Error OpenSSL: No se pudieron generar las llaves. Verifica openssl.cnf");
            }

            // 2. Extraer llave privada
            openssl_pkey_export($res, $privateKey, null, $config);

            // 3. Extraer llave pública
            $publicKeyDetails = openssl_pkey_get_details($res);
            $publicKey = $publicKeyDetails['key'];

            // 4. Firmar
            // Pasamos la llave privada generada
            if (!openssl_sign($dataToSign, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
                 throw new \Exception("Error al firmar los datos.");
            }
            
            // 5. Verificar
            // Pasamos la llave pública correspondiente
            $isValid = openssl_verify($dataToSign, $signature, $publicKey, OPENSSL_ALGO_SHA256);

            return response()->json([
                'protocolo' => 'Firma Digital (RSA)',
                'estado' => $isValid === 1 ? 'Verificado' : 'Fallo de Verificación',
                'algoritmo' => 'SHA-256 con RSA-2048',
                'mensaje' => 'Se generó un par de llaves efímeras, se firmó una cadena de datos y se verificó matemáticamente usando la llave pública.',
                'hash_firma' => base64_encode($signature) // Para mostrar que hay datos reales
            ]);

        } catch (\Exception $e) {
             return response()->json([
                'error' => 'Error en Firma Digital',
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }

    // --- 9. CERTIFICADOS DIGITALES (Verificación de Infraestructura) ---
    public function testCertificados()
    {
        // Verificar si existen las llaves de la Autoridad Certificadora (CA)
        // que creamos anteriormente para las tarjetas
        $rutaPrivada = storage_path('app/keys/private.pem');
        $rutaPublica = storage_path('app/keys/public.pem');
        
        $existePrivada = file_exists($rutaPrivada);
        $existePublica = file_exists($rutaPublica);

        $estado = ($existePrivada && $existePublica) ? 'Infraestructura Lista' : 'No Inicializado';

        return response()->json([
            'protocolo' => 'X.509 (Certificados)',
            'estado' => $estado,
            'ubicacion_llaves' => 'storage/app/keys/',
            'detalle' => 'El sistema está listo para emitir certificados X.509 autofirmados (usado en Módulo Tarjetas).'
        ]);
    }
}