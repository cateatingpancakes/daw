<?php

    require_once(__DIR__ . "/../mail/config.php");
    require_once(__DIR__ . "/../mail/class.phpmailer.php");

    function propagateValues($exclude = [], $by = null) 
    {
        $propStr = "";

        if($by == null)
        {
            foreach($_GET as $key => $value) 
            {
                if(!in_array($key, $exclude)) 
                {
                    $value = htmlspecialchars($value, ENT_QUOTES, "UTF-8");
                    $propStr .= "<input type=\"hidden\" name=\"" . htmlspecialchars($key) . "\" value=\"" . htmlspecialchars($value) . "\">";
                }
            }
        }
        else
        {
            foreach($by as $key => $value) 
            {
                if(!in_array($key, $exclude)) 
                {
                    $value = htmlspecialchars($value, ENT_QUOTES, "UTF-8");
                    $propStr .= "<input type=\"hidden\" name=\"" . htmlspecialchars($key) . "\" value=\"" . htmlspecialchars($value) . "\">";
                }
            } 
        }

        return $propStr;
    }

    function seatCodeOf($row, $col, $noCols)
    {
        return ($row * $noCols) + $col;
    }

    function rowOf($seat, $noCols)
    {
        return floor($seat / $noCols) + 1;
    }

    function colOf($seat, $noCols)
    {
        return ($seat % $noCols) + 1;
    }

    function strftime2($format, $date)
    {
        $datetime = new DateTime($date);
        $formatter = new IntlDateFormatter(
            "ro_RO",
            IntlDateFormatter::NONE,    
            IntlDateFormatter::NONE,     
            null,                        
            null,         
            $format  
        );

        $formatDate = $formatter->format($datetime);
        return $formatDate;
    }

    function mail2($who, $subject, $what, $name = "", $attach = [])
    {
        $mail = new PHPMailer(true);
        $mail->IsSMTP();

        try
        {
            $title = "Proiect DAW";
            $sender = mailcfg\mailcfg::MAIL_SENDER;

            $mail->SMTPDebug = 0;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "ssl";
            $mail->Host = mailcfg\mailcfg::MAIL_HOST;
            $mail->Port = 465;
            $mail->Username = mailcfg\mailcfg::MAIL_UNAME;
            $mail->Password = mailcfg\mailcfg::MAIL_PWORD;
            $mail->SetFrom($sender, $title);
            $mail->AddReplyTo($sender, $title);
            $mail->AddAddress($who, $name);
            
            $mail->Subject = $subject;
            $mail->AltBody = "Aveți nevoie de un viewer HTML compatibil.";
            $mail->MsgHTML($what);

            if(!empty($attach)) 
            {
                foreach($attach as $path) 
                {
                    if(file_exists($path)) 
                    {
                        $mail->addAttachment($path);
                    }
                }
            }

            return $mail->Send();
        }
        catch(phpmailerException $e) 
        {
            echo $e->getMessage();
            die();
        } 
        catch(Exception $e) 
        {
            echo $e->getMessage();
            die();
        }
    }

    function post($url, $params)
    {
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        $response = curl_exec($ch);

        return $response;
    }
?>