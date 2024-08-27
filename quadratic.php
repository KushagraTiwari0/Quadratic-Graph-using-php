<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: 'Georgia', serif;
            background-color: #1e1e1e;
            color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: linear-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 20px 20px;
        }
        .wrapper {
            display: flex;
            justify-content: space-between;
            width: 80%;
        }
        .container {
            background-color: #2c2c2c;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            width: 320px;
            margin-right: 20px;
        }
        h2 {
            color: #ffffff;
            font-family: 'Cambria', serif;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-size: 16px;
        }
        input[type="number"] {
            width: calc(100% - 16px);
            margin: 0 8px;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #1a1a1a;
            color: #ffffff;
        }
        input[type="submit"] {
            width: calc(100% - 16px);
            padding: 10px;
            margin: 0 8px;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 18px;
            cursor: pointer;
            margin-top: 15px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .right-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 600px;
        }
        .result-block {
            background-color: #333;
            padding: 15px;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            width: 100%;
        }
        .result-title {
            font-size: 22px;
            font-family: 'Cambria', serif;
            margin-bottom: 10px;
            color: #4CAF50;
        }
        .result {
            font-size: 20px;
            color: #ffffff;
            font-family: 'Courier New', Courier, monospace;
        }
        #graph-container {
            width: 100%;
            height: 400px;
            margin-top: 20px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="wrapper">
    <div class="container">
        <h2>Quadratic Equation Solver</h2>
        <form method="post">
            <label for="a">Coefficient a:</label>
            <input type="number" step="any" name="a" id="a" value="" required><br><br>

            <label for="b">Coefficient b:</label>
            <input type="number" step="any" name="b" id="b" value="" required><br><br>

            <label for="c">Coefficient c:</label>
            <input type="number" step="any" name="c" id="c" value="" required><br><br>

            <input type="submit" value="Calculate Roots">
        </form>
    </div>

    <div class="right-container">
        <div class="result-block">
            <div class="result-title">Roots of the Equation:</div>
            <div class="result">
                <?php
                $plotData = [];
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Get the coefficients from the form input
                    $a = $_POST['a'];
                    $b = $_POST['b'];
                    $c = $_POST['c'];

                    if ($a == 0) {
                        echo "Coefficient 'a' cannot be zero for a quadratic equation.";
                    } else {
                        // Calculate the discriminant
                        $discriminant = $b * $b - 4 * $a * $c;

                        // Check if the discriminant is positive, negative, or zero
                        if ($discriminant > 0) {
                            // Two real and distinct roots
                            $root1 = (-$b + sqrt($discriminant)) / (2 * $a);
                            $root2 = (-$b - sqrt($discriminant)) / (2 * $a);
                            echo "Root 1: " . $root1 . "<br>";
                            echo "Root 2: " . $root2;
                        } elseif ($discriminant == 0) {
                            // One real root (repeated)
                            $root = -$b / (2 * $a);
                            echo "Root: " . $root;
                        } else {
                            // Two complex roots
                            $realPart = -$b / (2 * $a);
                            $imaginaryPart = sqrt(-$discriminant) / (2 * $a);
                            echo "Root 1: " . $realPart . " + " . $imaginaryPart . "i<br>";
                            echo "Root 2: " . $realPart . " - " . $imaginaryPart . "i";
                        }

                        // Generate data points for the graph
                        for ($x = -10; $x <= 10; $x += 0.5) {
                            $y = $a * $x * $x + $b * $x + $c;
                            $plotData[] = ['x' => $x, 'y' => $y];
                        }
                    }
                }
                ?>
            </div>
        </div>

        <?php if (!empty($plotData)) : ?>
        <div id="graph-container">
            <canvas id="quadraticGraph"></canvas>
        </div>

        <script>
            const ctx = document.getElementById('quadraticGraph').getContext('2d');
            const plotData = <?php echo json_encode($plotData); ?>;
            const labels = plotData.map(point => point.x);
            const data = plotData.map(point => point.y);

            const quadraticGraph = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Quadratic Equation',
                        data: data,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                        fill: false,
                        pointRadius: 0
                    }]
                },
                options: {
                    scales: {
                        x: {
                            type: 'linear',
                            position: 'bottom',
                            title: {
                                display: true,
                                text: 'X'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Y'
                            }
                        }
                    }
                }
            });
        </script>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
