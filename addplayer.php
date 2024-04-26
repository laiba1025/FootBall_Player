<?php

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    $errors = array();

    if (empty($_POST["name"])) {
        $errors[] = "Enter a name!";
    } else {
        $name = sanitize_input($_POST["name"]);
        if (strlen($name) < 4 || trim($name) != $_POST["name"]) {
            $errors[] = "Enter a name that is at least 4 characters long!";
        }
    }

    if (empty($_POST["positions"])) {
        $errors[] = "Enter the positions!";
    } else {
        $positions = sanitize_input($_POST["positions"]);
        if (!preg_match('/^([^,]+)(,\s*[^,]+)*$/', $positions)) {
            $errors[] = "Enter the positions separated with commas!";
        }
    }

    if (empty($_POST["goals2024"])) {
        $errors[] = "Enter the number of goals!";
    } else {
        $goals2024 = sanitize_input($_POST["goals2024"]);
        if (!filter_var($goals2024, FILTER_VALIDATE_INT)) {
            $errors[] = "The number of goals must be integers!";
        }
    }

    if (empty($errors)) {

        $players_data = json_decode(file_get_contents('players.json'), true);

        $existing_player = false;
        foreach ($players_data as $player) {
            if ($player['name'] == $name) {
                $existing_player = true;
                break;
            }
        }

        $new_player = array(
            "name" => $name,
            "positions" => explode(",", $positions),
            "goals2023" => 0,
            "goals2024" => (int)$goals2024,
            "img" => $_POST["img"]
        );

        $players_data[] = $new_player;
        file_put_contents('players.json', json_encode($players_data, JSON_PRETTY_PRINT));

        header("Location: index.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en" data-theme="forest">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Player</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.10.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="header w-full text-3xl bg-neutral p-5 font-bold text-neutral-content text-center ">
        Roaster of Team Webprog
        <a class="btn btn-primary font-bold ml-10 mt-1" href="index.php">Main Page</a>
    </div>
    <div class="flex">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
            class="mx-auto mt-3 w-3/12 p-10">
            <h1 class="text-3xl  p-5 font-bold">Add a new player</h1>
            <?php
            
            if (!empty($errors)) {
                echo '<div class="alert alert-error mb-2">';
                foreach ($errors as $error) {
                    echo '<span>' . $error . '</span><br>';
                }
                echo '</div>';
            }
            ?>
            <label class="form-control w-full max-w-xs">
                <div class="label">
                    <span class="label-text">Name</span>
                </div>
                <input type="text" name="name" placeholder="Type here" class="input input-bordered w-full max-w-xs"
                    value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>" />
            </label>

            <label class="form-control w-full max-w-xs">
                <div class="label">
                    <span class="label-text">Goals</span>
                </div>
                <input type="number" name="goals2024" placeholder="Type here"
                    class="input input-bordered w-full max-w-xs"
                    value="<?php echo isset($_POST['goals2024']) ? $_POST['goals2024'] : ''; ?>" />
            </label>

            <label class="form-control w-full max-w-xs">
                <div class="label">
                    <span class="label-text">Positions</span>
                </div>
                <input type="text" name="positions" placeholder="Type here"
                    class="input input-bordered w-full max-w-xs"
                    value="<?php echo isset($_POST['positions']) ? $_POST['positions'] : ''; ?>" />
                <div class="label">
                    <span class="label-text-alt">Write down the positions separated with a coma! ','</span>
                </div>
            </label>

            <select class="select w-full max-w-xs mb-3 select-bordered" name="img">
                <option disabled selected>Select the picture</option>
                <option value="batorini">Batorini</option>
                <option value="benke">Benke</option>
                <option value="carlaise">Carlaise</option>
                <option value="cher">Cher</option>
                <option value="dace">Dace</option>
                <option value="kiss">Kiss</option>
            </select>
            <input type="submit" value="Add New Player" class="btn btn-primary font-bold">
        </form>
    </div>
</body>

</html>
