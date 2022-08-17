<?php

namespace App;
use PHPMailer\PHPMailer\PHPMailer;

class Helper
{
    public static function removeMascFone($fone)
    {
        if ($fone) {
            $cep = str_replace("-", "", $fone);
            $cep = str_replace("(", "", $cep);
            $cep = str_replace(")", "", $cep);
            $cep = str_replace(".", "", $cep);
            $cep = str_replace(" ", "", $cep);
            return trim($cep);
        }
        return "";
    }

    public  static function sendEmail($assunto, $text, $emissor)
    {
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->Subject = $assunto;
        $mail->SMTPAuth = true;
        $mail->Username = 'ecomodasobral@gmail.com';
        $mail->Password = 'ylzatowvawrekvxb';
        $mail->SMTPSecure = 'ssl';
        $mail->IsHTML(true);
        $mail->CharSet = 'utf-8';
        $mail->SetFrom('ecomodasobral@gmail.com', "Ecomoda", 0);
        $mail->AddAddress($emissor);

        $imagem_topo = '<img src="http://ecoback.herokuapp.com/assets/img/logo-01.png" alt="topo" border="0" style="max-width:800px; max-height:150px; width: auto;
    height: auto;"/>';

        $rodape = '</p> <br />
				<font style="display:block; text-align: center; margin: 30px auto 0 auto; position: relative" color"#000000">
                    Esta mensagem foi enviada de um endereço de e-mail que apenas envia mensagens.<br>
                    Para obter mais informações sobre sua conta, envie e-mail para: ecomodasobral@gmail.com
                    <br /><br />
                    &copy; ' . date('Y') . ' Todos os direitos reservados Ecomoda
                </font><br />
                ';

        $msga=$imagem_topo;
        $msga =$msga . $text;
        $msga = $msga. $rodape;
        $mail->msgHTML($msga);
        $mail->Port = 465;
        //$mail->SMTPDebug  = 1;
        $msg = $mail->Send();
    }
}
