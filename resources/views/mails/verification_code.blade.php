<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plantilla de Correo Electrónico</title>
    <style>
        body, html {
            margin: 0 !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
            font-family: Arial, sans-serif;
        }
        
        .body-container {
            background-color: #f0f0f0;
            padding: 40px 20px;
            width: 100%;
            min-height: 100vh;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .card-container {
            max-width: 600px;
            width: 100%;
            margin: 0 auto;
        }
        
        .card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
        }
        
        .logo {
            width: 100px;
            height: auto;
        }
        
        .header-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .header-text {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        
        .color-green {
            color: #44ac04 !important;
        }
        
        .color-gold {
            color: #d4ac04 !important;
        }
        
        .color-gray {
            color: #555555 !important;
        }
        
        .separator {
            border-bottom: 1px solid #ddd;
            margin: 20px 0;
            width: 100%;
        }
        
        .header {
            font-size: 22px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }
        
        .code {
            font-size: 28px;
            font-weight: bold;
            color: #007BFF;
            background: #eef5ff;
            padding: 10px 20px;
            display: inline-block;
            margin: 20px 0;
            border-radius: 6px;
        }
        
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #007BFF;
            color: #ffffff !important;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            border-radius: 6px;
            margin-top: 20px;
        }
        
        .footer {
            color: #666;
            font-size: 14px;
            margin-top: 20px;
        }
        
        .content-section {
            margin: 15px 0;
        }
        
        .link-text {
            color: #333;
            font-size: 14px;
            margin: 20px 0 0 0;
        }
        
        .link {
            color: #007BFF;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="body-container" style="background-color: #f0f0f0; padding: 40px 20px; width: 100%; min-height: 100vh; box-sizing: border-box; display: flex; align-items: center; justify-content: center;">
        <div class="card-container" style="max-width: 600px; width: 100%; margin: 0 auto;">
            <div class="card" style="background-color: #ffffff; border-radius: 10px; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); padding: 30px; text-align: center;">
                
                <!-- Logo y título -->
                <div class="header-container" style="display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 15px;">
                    <img src="{{ $message->embed($pathToImage) }}" alt="Logo" class="logo" style="width: auto; height: auto;">
                    <h2 class="header-text" style="font-size: 24px; font-weight: bold; margin: 0;">
                        <span style="color: #44ac04;">REC</span><span style="color: #d4ac04;">OILS</span>
                    </h2> 
                </div>
                
                <!-- Título principal -->
                <h1 class="title" style="color: #1a1f36; font-size: 32px; font-weight: 700; margin-bottom: 24px; line-height: 1.2;">
                    Confirma tu correo electrónico
                </h1>

                <!-- Código de verificación -->
                <div class="content-section" style="margin: 15px 0; justify-content: start;">
                    <p class="code" style="font-size: 28px; font-weight: bold; color: #007BFF; background: #eef5ff; padding: 10px 20px; display: inline-block; margin: 20px 0; border-radius: 6px;">{{ $email }}</p>
                </div>
                
                <!-- Instrucciones -->
                <div class="content-section" style="margin: 15px 0;">
                    <p style="color: #333; margin-bottom: 20px;">Para confirmar tu dirección de correo electrónico haz click en el siguiente botón:</p>
                </div>
                
                <!-- Botón -->
                <div class="content-section" style="margin: 15px 0;">
                    <a href="{{ route('user_verification_confirm', ['email' => $email, 'verification_token' => $token])}}" class="button" style="display: inline-block; padding: 12px 24px; background-color: #007BFF; color: #ffffff; text-decoration: none; font-size: 18px; font-weight: bold; border-radius: 6px;">Verificar Cuenta</a>
                </div>
                
                <!-- Enlace alternativo -->
                <div class="content-section" style="margin: 15px 0;">
                    <p class="link-text" style="color: #333; font-size: 14px; margin: 20px 0 0 0;">
                        Si no puedes hacer click en el botón, copia y pega el siguiente enlace en un navegador: 
                        <!-- <a href="#" class="link" style="color: #007BFF; text-decoration: underline;">{{ url("/verified/email/$email/$token") }}</a> -->
                        <a href="{{ route('user_verification_confirm', ['email' => $email, 'verification_token' => $token]) }}" class="link" style="color: #007BFF; text-decoration: underline;">{{ route('user_verification_confirm', ['email' => $email, 'verification_token' => $token]) }}</a>
                    </p>
                </div>               
            </div>
        </div>
    </div>
</body>
</html>