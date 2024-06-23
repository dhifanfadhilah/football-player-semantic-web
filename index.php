<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="./styles.css">

    <title>Football Player</title>
</head>

<body>
    <?php
    require_once("sparqllib.php");
    $test = "";
    if (isset($_POST['search'])) {
        $test = $_POST['search'];
        $data = sparql_get(
            "http://localhost:3030/football",
            "
      PREFIX p: <http://football.com>
      PREFIX d: <http://football.com/ns/data#>
      
          SELECT ?namaPlayer ?klub ?Posisi ?marketValue ?tinggi ?negara ?photos
          WHERE
         { 
              ?s  d:namaPlayer ?namaPlayer ;
                  d:klub ?klub;
                  d:Posisi ?Posisi;
                  d:marketValue ?marketValue;
                  d:tinggi ?tinggi;
                  d:negara ?negara;
                  d:photos ?photos
              FILTER (regex (?namaPlayer,  '$test', 'i'))
            }"
        );
    } else{
        $data = sparql_get(
            "http://localhost:3030/football",
            "
      PREFIX p: <http://football.com>
      PREFIX d: <http://football.com/ns/data#>
      
          SELECT ?namaPlayer ?klub ?Posisi ?marketValue ?tinggi ?negara ?photos
          WHERE
          { 
              ?s  d:namaPlayer ?namaPlayer ;
                  d:klub ?klub;
                  d:Posisi ?Posisi;
                  d:marketValue ?marketValue;
                  d:tinggi ?tinggi;
                  d:negara ?negara;
                  d:photos ?photos
          }

            "
        );
    }

    if (!isset($data)) {
        print "<p>Error: " . sparql_errno() . ": " . sparql_error() . "</p>";
    }
    ?>

    <section id="about">
        <div class="container">
            <h1 class="mb-3">FOOTBALL PLAYER</h1>

            <!-- Hasil Pencarian -->

            <div class="row text-center mb-3 mt-0 hasil">
                <div class="col">
                    <form action="" method="post" id="nameform">
                        <div class="search-box">
                            <label for="search"><h2>Search your favorite player</h2></label><br>
                            <input type="text" name="search" placeholder="Player Name..." />
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row fs-5">
                <div class="col-md-5">
                    <p>
                        Hasil Pencarian:
                        <span>
                            <?php
                            if ($test != NULL) {
                                echo $test;
                            } else {
                                echo "";
                            }
                            ?>
                        </span>
                    </p>
                    
                </div>
            </div>

            <div class="row">
                <?php $i = 0; ?>
                <?php foreach ($data as $dat) : ?>
                    <div class="col-md-4">
                        <div class="card" style="width: 18rem;">
                            <img src="<?= $dat['photos'] ?>" class="card-img-top object-fit-cover" style="height:18rem" alt="...">
                            <div class="card-body">
                                <h3 class="card-title"><?= $dat['namaPlayer'] ?></h3>
                                <h4 class="card-text"><?= $dat['klub'] ?></h4>
                                <p class="card-text"><span><?= $dat['Posisi'] ?></span> | <span><?= $dat['tinggi'] ?> cm</span></p>
                                <p class="card-text"><?= $dat['negara'] ?></p>
                                <p class="card-text">Market Value €<?= $dat['marketValue'] ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>