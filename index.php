<!-- index.php -->

<!DOCTYPE html>
<html lang="en" data-theme="forest">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Players</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.10.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="header w-full text-3xl bg-neutral p-5 font-bold text-neutral-content text-center ">
        Roster of Team Webprog
        <a class="btn btn-primary font-bold ml-10 mt-1" href="addplayer.php">Add player</a>
    </div>
    <div class="w-[80vw] mx-auto mt-5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 max-h-[80vh] overflow-y-scroll ">
        <?php
        $playersData = file_get_contents('players.json');
        $players = json_decode($playersData, true);
  
        foreach ($players as $player) {
            echo '<div class="card card-side bg-base-300 shadow-xl">';
            echo '<figure class="h-full"><img src="./img/' . $player['img'] . '.jpg" class="h-full w-48 object-cover" /></figure>';
            echo '<div class="card-body mx-auto text-center w-full p-3 my-auto">';
            echo '<h2 class="card-title text-center block">' . $player['name'] . '</h2>';

            $positions = $player['positions'];
            echo '<div class="card-actions mx-auto text-center block">';
            foreach ($positions as $index => $position) {
                $badgeClass = ($index == 0) ? 'badge-primary' : 'badge-outline';
                echo '<div class="badge ' . $badgeClass . '">' . $position . '</div>';
            }
            echo '</div>';

            $goals2024 = $player['goals2024'];
            $goals2023 = $player['goals2023'];
            $difference = $goals2024 - $goals2023;
            $percentageDifference = ($goals2023 == 0 || $goals2024 == 0) ? 'N/A' : round(($difference / $goals2023) * 100) . '%';

            echo '<div class="stat">';
            echo '<div class="stat-title">Goals this season</div>';
            echo '<div class="stat-value">' . $goals2024 . '</div>';
            if ($goals2023 == 0) {
                echo '<div class="stat-desc">It\'s a new player</div>';
            } else {
                if ($difference < 0) {
                    echo '<div class="stat-desc">Less than last season</div>';
                } elseif ($difference > 0) {
                    echo '<div class="stat-desc">' . $percentageDifference . ' more than last season</div>';
                } else {
                    echo '<div class="stat-desc">Same as last season</div>';
                }
            }
            echo '</div>';

            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
</body>

</html>
