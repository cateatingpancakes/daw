<?php
    
    require_once(__DIR__ . "/utility.php");
    require_once(__DIR__ . "/../require/auth.php");

    class Draw
    {
        private function __construct() 
        {
            // No constructor, static-method class.
        }

        public static function movieCard($projId, $movieName, $runtime, $date, $roomName, $drawAdmin = false)
        {
            $formatDate = strftime2("d MMMM HH:mm", $date);
            $propStr = propagateValues(["PROJ_CHOICE", "SEAT_CHOICE"]);

            echo    "<div class=\"card mb-3\">
                        <div class=\"card-body\">
                            <h5 class=\"card-title\">". htmlspecialchars($movieName) . "</h5>
                            <p class=\"card-text\">Pe " . htmlspecialchars($formatDate) .", în " . htmlspecialchars($roomName) . " <i style=\"color:gray\">(". htmlspecialchars($runtime) . " minute)</i></p>

                            <div class=\"input-group\">
                                <form method=\"GET\" action=\"index.php\" class=\"\">
                                    $propStr
                                    <input type=\"hidden\" name=\"PROJ_CHOICE\" value=\"" . htmlspecialchars($projId) . "\">
                                    <button type=\"submit\" class=\"btn btn-primary me-1\">Selectează</button>
                                </form>";

                            if($drawAdmin) 
                            {
                                echo
                                "
                                 <form method=\"POST\" action=\"action/action_projupdate.php\">
                                    <input type=\"hidden\" name=\"PROJ_CHOICE\" value=\"" . htmlspecialchars($projId) . "\">
                                    <button class=\"btn btn-success me-1\">Modifică</button>
                                 </form>

                                 <form method=\"POST\" action=\"action/action_projdelete.php\" target=\"action-frame\">";

                                 CSRF::input();

                                echo "
                                    <input type=\"hidden\" name=\"PROJ_CHOICE\" value=\"" . htmlspecialchars($projId) . "\">
                                    <button type=\"submit\" class=\"btn btn-danger me-1\">Șterge</button>
                                 </form>";
                            }

            echo            "</div>
                        </div>
                    </div>";
        }

        public static function movieInsertCard()
        {
            $timeNow = date("H:i");
            $date = null;

            if(isset($_GET["MOVIE_DATE"]) && $_GET["MOVIE_DATE"] != null)
            {
                $date = $_GET["MOVIE_DATE"];
            }
            else
            {
                $date = date("Y-m-d");
            }

            echo    "<div class=\"card mb-3\">
                        <div class=\"card-body\">
                            <form method=\"POST\" action=\"action/action_projcreate.php\" target=\"action-frame\">";

                                CSRF::input();

            echo               "<input type=\"hidden\" name=\"DATE\" value=\"" . htmlspecialchars($date) . "\">

                                <div class=\"mb-3\">
                                    <select class=\"form-select\" name=\"MOVIE_ID\">";

                                    $movies = Query::getAll("movie");
                                    foreach($movies as $record)
                                    {
                                        $id = $record["MOVIE_ID"];
                                        $name = $record["NAME"];
                                        echo "<option value=\"". htmlspecialchars($id) . "\">" . htmlspecialchars($name) . "</option>";
                                    }

            echo                   "</select>
                                </div>

                                <div class=\"mb-3\">
                                    <select class=\"form-select\" name=\"ROOM_ID\">";

                                    $rooms = Query::getAll("room");
                                    foreach($rooms as $record)
                                    {
                                        $id = $record["ROOM_ID"];
                                        $name = $record["NAME"];
                                        echo "<option value=\"". htmlspecialchars($id) . "\">" . htmlspecialchars($name) . "</option>";
                                    }

            echo                   "</select>
                                </div>

                                <div class=\"mb-3\">
                                    <input type=\"time\" class=\"form-control\" name=\"TIME\" value=\"". htmlspecialchars($timeNow) . "\" required>
                                </div>
                                
                                <button type=\"submit\" class=\"btn btn-primary me-1\">Adaugă</button>
                            </form>
                        </div>
                    </div>";
        }

        private static function movieTableStyle()
        {
            echo 
               "<style>
                    .seat-grid {
                        border-collapse: separate; 
                        border-spacing: 3px; 
                    }
                    .seat-grid td {
                        padding: 0; 
                    }
                    .seat-grid .btn {
                        width: 34px;
                        height: 34px;
                        box-sizing: border-box;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        padding: 0;
                        border-radius: 0;
                        font-family: 'Consolas', monospace;
                    }
                </style>";
        }

        public static function movieTable($noRows, $noCols, $taken = [])
        {
            self::movieTableStyle();
            echo   "<table class=\"table table-borderless seat-grid w-auto mx-auto\">
                    <tbody>";

            for ($row = 0; $row < $noRows; $row++) 
            {
                echo "<tr>";
                for ($col = 0; $col < $noCols; $col++) 
                {
                    $seatCode = seatCodeOf($row, $col, $noCols);
                    $columnIndex = $col + 1;

                    echo "<td class=\"p-0\">";

                    if (in_array($seatCode, $taken)) 
                    {
                        echo "<button type=\"button\" class=\"btn btn-danger text-white\" disabled>" . htmlspecialchars($columnIndex) . "</button>";
                    } 
                    else 
                    {
                        $propStr = propagateValues(["SEAT_CHOICE"]);
                        $btnType = null;

                        if(isset($_GET["SEAT_CHOICE"]) && $_GET["SEAT_CHOICE"] == $seatCode)
                            $btnType = "warning";
                        else
                            $btnType = "success";
                        

                        echo    "<form method=\"GET\" action=\"index.php\" class=\"m-0\">
                                    $propStr
                                    <input type=\"hidden\" name=\"SEAT_CHOICE\" value=" . htmlspecialchars($seatCode) . ">
                                    <button type=\"submit\" class=\"btn btn-$btnType text-white\">" . htmlspecialchars($columnIndex) . "</button>
                                </form>";
                    }
                    echo "</td>";
                }
                echo "</tr>";
            }

            echo "</tbody></table>";
        }

        public static function purchaseBox($tickets, $drawCaptcha = false)
        {
            echo "<div class=\"border p-4 mt-3\" style=\"background-color: #f8f9fa;\">
                  <h5>Finalizează comanda</h5>";

            echo "<div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">
                      <strong>Atenție!</strong> Aplicația chiar va încerca să trimită mail la adresa specificată.
                      <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
                  </div>";

            $propStr = propagateValues(["TICKET_CHOICE"]);

            echo "<form action=\"purchase.php\" method=\"POST\" target=\"_blank\">
                    <div class=\"mb-3 text-start\">
                    $propStr
                        <label for=\"purchaseTicket\" class=\"form-label\">Categorie bilet</label>
                        <select class=\"form-select\" id=\"purchaseTicket\" name=\"TICKET_CHOICE\">";

                            foreach($tickets as $ticket)
                            {
                                $id    = $ticket["TICKET_ID"];
                                $desc  = $ticket["DESC"];
                                $price = $ticket["PRICE"];

                                echo "<option value=\"" . htmlspecialchars($id) . "\" data-price=\"" . htmlspecialchars($price) . "\">" . htmlspecialchars($desc) . "</option>";
                            }

            echo       "</select>
                    </div>";

            $userData = Auth::getData();
            if($userData)
            {
                $userEmail = $userData["email"];
                echo "<div class=\"text-start\">
                        <label for=\"purchaseEmail\" class=\"form-label\">Adresă email</label>
                        <input type=\"text\" class=\"form-control\" id=\"purchaseEmail\" name=\"TICKET_EMAIL\" value=\"" . htmlspecialchars($userEmail) . "\" required disabled> 
                      </div>";
            }
            else
            {
                echo "<div class=\"text-start\">
                        <label for=\"purchaseEmail\" class=\"form-label\">Adresă email</label>
                        <input type=\"text\" class=\"form-control\" id=\"purchaseEmail\" name=\"TICKET_EMAIL\" required>
                      </div>";
            }

            
            if($drawCaptcha)
            {
                echo "<div class=\"mt-3 g-recaptcha\" data-sitekey=\"" . Auth::CAPTCHA_PUBLIC . "\"></div>";
            }

            echo   "<div class=\"text-start mt-3\">
                        <p class=\"fw-bold fst-italic\">Preț lei <span class=\"text-success\" id=\"ticketPrice\"></span></p>
                        <button type=\"submit\" class=\"btn btn-primary\">Cumpără</button>
                    </div>
                    </form>
                  </div>";
        }
        
        public static function loginLink($accountUrl = "account.php", $loginUrl = "login.php")
        {
            $userData = Auth::getData();
            
            if(isset($userData))
            {
                $username = $userData["username"];
                echo "<li class=\"nav-item\"><a class=\"nav-link\" href=\"" . htmlspecialchars($accountUrl) . "\">Cont (" . htmlspecialchars($username) . ")</a></li>";
            }
            else
            {
                echo "<li class=\"nav-item\"><a class=\"nav-link\" href=\"" . htmlspecialchars($loginUrl) . "\">Login</a></li>";
            }
        }

        public static function accountInvalidForm()
        {
            echo   "<p>Contul tău nu este validat. Dacă vrei să-ți gestionezi comenzile din cont, trebuie să îl confirmi. Îți putem retrimite mesajul de confirmare mai jos.</p>
                    <div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">
                        <strong>Atenție!</strong> Aplicația chiar va încerca să trimită mail la adresa specificată.
                        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
                    </div>
                    <form action=\"action/action_resendconf.php\" method=\"POST\" target=\"action-frame\" class=\"mt-3\">";

                        CSRF::input();

            echo       "<button type=\"submit\" class=\"btn btn-danger btn-primary\">Retrimite</button>
                    </form>";
        }

        public static function accountValidForm($tickets)
        {
            if(empty($tickets)) 
            {
                echo "<p class=\"text-muted\">Nu ai bilete active.</p>";
            }
            else
            {
                foreach($tickets as $ticket) 
                {
                    $unixDate = strtotime($ticket["P_DATE"]);
                
                    if($unixDate > time())
                    {
                        $date = strftime2("d MMMM HH:mm", $ticket["P_DATE"]);

                        $noCols = $ticket["N_COLS"];
                        $row = rowOf($ticket["SEAT"], $noCols);
                        $col = colOf($ticket["SEAT"], $noCols);

                        $movieName    = $ticket["M_NAME"];
                        $movieRuntime = $ticket["M_RUNTIME"];
                        $roomName     = $ticket["R_NAME"];
                        $ticketType   = $ticket["T_DESC"];
                        $ticketPrice  = $ticket["T_PRICE"];
                        $saleId       = $ticket["SALE_ID"];
                        $saleCode     = $ticket["CHECK_CODE"];
                        $projDate     = $ticket["P_DATE"];

                        echo   "<div class=\"card mb-3\">
                                    <div class=\"card-body m-2 p-2\">
                                        <div>
                                            <h5 class=\"card-title mb-1\">" . htmlspecialchars($movieName) . "</h5>
                                            <p class=\"card-text mb-2\">
                                                " . htmlspecialchars($date) . " în " . htmlspecialchars($roomName) . " <br>
                                                Rând " . htmlspecialchars($row) . ", loc " . htmlspecialchars($col) . " <i><span class=\"text-muted\">(" . htmlspecialchars($ticketType) . ", " . htmlspecialchars($ticketPrice) . " lei)</span></i>
                                            </p>
                                        </div>

                                        <form action=\"ticket.php\" method=\"POST\" class=\"mt-2 d-inline\">";

                                            CSRF::input();

                        echo                propagateValues([], [
                                                "MOVIE_NAME"    => $movieName,
                                                "MOVIE_RUNTIME" => $movieRuntime,
                                                "ROOM_NAME"     => $roomName,
                                                "TICKET_TYPE"   => $ticketType,
                                                "TICKET_PRICE"  => $ticketPrice,
                                                "SEAT_ROW"      => $row,
                                                "SEAT_COL"      => $col,
                                                "SALE_CODE"     => $saleCode,
                                                "PROJ_DATE"     => $projDate
                                            ]);

                        echo               "
                                            <button type=\"submit\" class=\"btn btn-success\">Vizualizează</button>
                                        </form>

                                        <form action=\"action/action_ticketdelete.php\" method=\"POST\" target=\"action-frame\" class=\"mt-2 d-inline\">";

                                            CSRF::input();

                        echo               "<input type=\"hidden\" name=\"SALE_ID\" value=\"" . htmlspecialchars($saleId) . "\">
                                            <button type=\"submit\" class=\"btn btn-danger\">Anulează</button>
                                        </form>
                                    </div>
                                </div>";
                    }
                }
            }
        }
    }

?>