<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Farmer Dashboard - MarketLink</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f7f5;
            color: #222;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 240px;
            height: 100vh;
            background: #1f5c3a;
            color: white;
            padding: 25px 15px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 35px;
            padding-left: 10px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 13px 12px;
            margin-bottom: 5px;
            border-radius: 6px;
        }

        .sidebar a:hover {
            background: #2d754b;
        }

        .main {
            margin-left: 240px;
            padding: 30px;
        }

        .header {
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 8px;
        }

        .header p {
            color: #666;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .card {
            background: white;
            padding: 22px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .card h3 {
            color: #666;
            font-size: 15px;
            margin-bottom: 10px;
        }

        .card .number {
            font-size: 28px;
            font-weight: bold;
        }

        .section {
            background: white;
            margin-top: 25px;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        @media (max-width: 900px) {
            .cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main {
                margin-left: 0;
            }

            .cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <aside class="sidebar">
        <div class="logo">
            MarketLink
        </div>

        <a href="#">Dashboard</a>
        <a href="#">My Profile</a>
        <a href="#">Markets</a>
        <a href="#">Products</a>
        <a href="#">Pickup Slots</a>
        <a href="#">Orders</a>
        <a href="#">Reviews</a>
    </aside>

    <main class="main">

        <div class="header">
            <h1>Farmer Dashboard</h1>
            <p>Manage your products, markets, pickup slots and orders.</p>
        </div>

        <div class="cards">

            <div class="card">
                <h3>Total Products</h3>
                <div class="number">0</div>
            </div>

            <div class="card">
                <h3>Active Orders</h3>
                <div class="number">0</div>
            </div>

            <div class="card">
                <h3>Markets</h3>
                <div class="number">0</div>
            </div>

            <div class="card">
                <h3>Reviews</h3>
                <div class="number">0</div>
            </div>

        </div>

        <div class="section">
            <h2>Recent Orders</h2>
            <p style="margin-top: 10px; color: #666;">
                No orders yet.
            </p>
        </div>

    </main>

</body>
</html>