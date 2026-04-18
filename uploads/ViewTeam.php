<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rugby Positions</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        body {
            text-align: center;
            background-color: #2e7d32;
        }
        .field-container {
            position: relative;
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            background: url('<?= base_url("assets/images/Heidelberg  field.png") ?>') no-repeat center;
            background-size: cover;
            height: 1550px;
            padding: 20px;
        }
        .player {
            position: absolute;
            width: 40px; 
            height: 40px;
            text-align: center;
            font-weight: bold;
            color: white;
            
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            padding: 3px;
        }
        .player img {
            width: 180px; /* Increased size */
            height: 180px; /* Increased size */
            object-fit: cover;
            margin-bottom: 3px;
        }
        .player-name {
            position: absolute;
            top: 70px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 18px;
            color: black;
            text-shadow: 1px 1px 2px black;
            white-space: nowrap;
            background: rgba(255, 255, 255, 0.7); /* Blue transparent background */
            text-shadow: 1px 1px 3px rgba(255, 255, 255, 0.5); /* White shadow for visibility */
            border-radius: 8px; /* Add rounded edges */
            padding: 2px 8px; /* Add padding for better spacing */
        }
        .pos-1 { top: 78%; left: 78%; }
        .pos-2 { top: 78%; left: 63%; }
        .pos-3 { top: 78%; left: 48%; }
        .pos-4 { top: 68%; left: 56%; }
        .pos-5 { top: 68%; left: 69.5%; }
        .pos-6 { top: 68%; left: 43%; }
        .pos-7 { top: 68%; left: 83%; }
        .pos-8 { top: 60%; left: 63%; }
        .pos-9 { top: 50%; left: 60%; }
        .pos-10 { top: 45%; left: 48%; }
        .pos-11 { top: 25%; left: 80%; }
        .pos-12 { top: 39%; left: 37%; }
        .pos-13 { top: 35%; left: 25%; }
        .pos-14 { top: 25%; left: 25%; }
        .pos-15 { top: 20%; left: 53%; }
        
        /* Button styling */
        .download-btn {
            margin: 20px;
            padding: 10px 20px;
            background-color: #ffcc00;
            color: black;
            border: none;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }
        .download-btn:hover {
            background-color: #e6b800;
        }

        .bench-table {
            position: absolute;
            top: 880px;
            left: 30%;
            transform: translateX(-50%);
            width: 180px;
            height: 145px;
            text-align: center;
            background: rgba(255, 255, 255, 0.6); /* Light background for better contrast */
            border-collapse: collapse;
            font-size: 14px;
            z-index: 10;
            font-weight: bold;
            overflow-y: auto;
            color: black;
            border-radius: 8px; /* Soft rounded edges */
            padding: 5px;
        }

        /* Bench table cells */
        .bench-table th, .bench-table td {
            border: 1px solid white;
            padding: 5px;
            background: transparent; 
            text-shadow: 1px 1px 3px rgba(255, 255, 255, 0.5); /* White shadow for visibility */
        }

        /* Bench table header */
        .bench-table th {
            font-weight: bold;
            background: transparent;
        }

        .player-photo {
            width: 100px; /* Default size for player images */
            height: 100px;
            object-fit: cover;
        }

        /* Special styling for the no-image placeholder */
        .player-photo.placeholder-image {
            width: 50px; /* Smaller size for placeholder */
            height: 50px;
            opacity: 0.7; /* Optional: Make it semi-transparent */
            border: 2px dashed #ccc; /* Optional: Add a dashed border for better visual indication */
        }

        .game-details {
            position: absolute;
            top: 33%; /* Adjust to position in the center top */
            left: 55%;
            transform: translate(-50%, -50%);
            text-align: center;
            z-index: 10;
            color: white;
            font-family: Arial, sans-serif;
            font-size: 14px; /* Smaller font size */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8); /* Shadow for visibility on grass */
        }

        .game-details .matchup {
            font-size: 18px; /* Slightly larger for the matchup line */
            font-weight: bold;
            margin-bottom: 10px;
        }

        .game-details .venue, .game-details .time {
            font-size: 14px; /* Smaller font size for venue and time */
        }

        #spinner .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        #spinner .spinner {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #ffcc00;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
            margin-bottom: 10px;
        }

        #spinner .spinner-text {
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            text-shadow: 1px 1px 2px black;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

    </style>
</head>
<body>

<button id="printBtn" class="download-btn">
    <i class="fa fa-print"></i> Print
</button>

    <div id="contentToPrint" class="field-container">
        <?php 
        // Ensure variables are properly set
        $HomeClubName = $getPlayers[0]->HomeClubName ?? 'Home Club';
        $VisitorClubName = $getPlayers[0]->VisitorClubName ?? 'Visitor Club';
        $HomeGround = $getPlayers[0]->HomeGround ?? 'Unknown Venue';
        $Gametime = $getPlayers[0]->Gametime ?? 'Unknown Time';
        $teamFor = $teamFor ?? 'unknown_team'; 
 

        // Round the time to HH:MM format
        $roundedTime = substr($Gametime, 0, 5);

        // Store the game details
        $gameDetails = [
            'matchup' => sprintf("%s VS %s", htmlspecialchars($HomeClubName), htmlspecialchars($VisitorClubName)),
            'venue' => sprintf("Venue: %s", htmlspecialchars($HomeGround)),
            'time' => sprintf("Time: %s", htmlspecialchars($roundedTime))
        ];

        $benchPlayers = []; // Array to store bench players
        $totalWeight = 0;    // Initialize total weight for first 8 players

        foreach ($getPlayers as $player) { 
            // Construct the folder name dynamically
            $playerFolder = urlencode($player->telephone ?? '') . "_" . urlencode(str_replace(' ', '_', $player->name ?? ''));
            $playerImagePath = FCPATH . "uploads/Users/" . $playerFolder . "/" . $player->TeamImage; // Server path
            $playerImage = (!empty($player->TeamImage) && file_exists($playerImagePath))
            ? base_url() . "uploads/Users/" . $playerFolder . "/" . $player->TeamImage
            : base_url() . "uploads/Users/no-image.png"; // URL

            // Check if the current image is the placeholder
            $isPlaceholder = ($playerImage === base_url() . "uploads/Users/no-image.png");

            if ($player->posId <= 15) { // Show only players with posId 1-15 on the field
                // Add weight of first 8 players
                if ($player->posId <= 8) {
                    $totalWeight += $player->Weight; // Assuming weight is stored in $player->Weight
                }
                // Truncate player name after the first whitespace
                $truncatedName = htmlspecialchars(strtok($player->name ?? '', ' '));
            ?>
                <div class="player pos-<?= htmlspecialchars($player->posId ?? '') ?>">
                    <img src="<?= $playerImage ?>" 
                         alt="<?= $truncatedName ?>" 
                         class="player-photo <?= $isPlaceholder ? 'placeholder-image' : '' ?>">
                    <div class="player-name"><?= $truncatedName ?></div>
                </div>
            <?php 
            } else { 
                // Store bench players in an array for later display
                $benchPlayers[] = $player;
            }
        } 
        ?>

        <!-- Display Game Details in the Center Top -->
        <div class="game-details">
            <div class="matchup"><?= $gameDetails['matchup'] ?></div>
            <div class="venue"><?= $gameDetails['venue'] ?></div>
            <div class="time"><?= $gameDetails['time'] ?></div>
            <div class="Scrum Weight" id="total-weight">Scrum Weight: <?= $totalWeight ?> KG</div>
        </div>


            <table class="bench-table">
                <thead>
                    <tr>
                        <th>Position</th>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $hasBenchPlayers = false;
                    foreach ($getPlayers as $player) {
                        if ($player->posId > 15) {
                            $hasBenchPlayers = true;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($player->posId ?? '') ?></td>
                            <td><?= htmlspecialchars(strtok($player->name ?? '', ' ')) ?></td>
                        </tr>
                    <?php } } ?>
                    
                    <?php if (!$hasBenchPlayers) { ?>
                        <tr>
                            <td colspan="2">No bench players available.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div id="spinner" style="display: none;">
            <div class="spinner-overlay">
                <div class="spinner"></div>
                <div class="spinner-text">Generating image...</div>
            </div>
        </div>
            <?php if (!empty($getPlayers)) {
    $info = $getPlayers[0];
?>

    <script>

    // Pass PHP variables to JS

    function downloadImage() {
        const fieldContainer = document.getElementById("contentToPrint");
    }

    const fieldContainer = document.getElementById("rugbyField");
    

    // Safely pass PHP data into JavaScript
    const teamFor = <?= json_encode($info->TeamFor) ?>;
    const homeClub = <?= json_encode($info->HomeClubName) ?>;
    const visitorClub = <?= json_encode($info->VisitorClubName) ?>;

    document.getElementById("printBtn").addEventListener("click", function () {
        const filename = `${homeClub}_vs_${visitorClub}_${teamFor}.png`.replace(/\s+/g, '_');

        html2canvas(document.querySelector("#contentToPrint")).then(canvas => {
            const link = document.createElement("a");
            link.download = filename;
            link.href = canvas.toDataURL();
            link.click();
        });
    });
</script>
<?php } ?>

</body>
</html>