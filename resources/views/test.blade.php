<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #eff2ff;
            margin: 0;
            padding: 0;
        }

        .services {
            max-width: 1200px;
            margin: 60px auto;
            padding: 0 20px;
        }

        .services-header {
            text-align: center;
            font-family: sans-serif;
            margin-bottom: 40px;
        }

        .services-header h2 {
            font-size: 42px;
            color: #242a56;
            margin-bottom: 20px;
            font-family: sans-serif;
            font-style: bold;
        }

        .book-btn {
            background-color: #4d61d6;
            color: white;
            border: none;
            margin-top: 15px;
            padding: 12px 24px;
            font-size: 1rem;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .book-btn:hover {
            background-color: #005ea2;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 35px;
        }

        .service-card {
            background-color: white;
            border-radius: 10px;
            padding: 30px 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-5px);
        }

        .icon {
            font-size: 42px;
            margin-bottom: 15px;
            display: block;
            color: #6878d6;

        }

        .service-card h3 {
            font-size: 20px;
            font-family: sans-serif;
            color: #242a56;
            margin-bottom: 10px;
        }


        .service-card p {
            font-size: 0.95rem;
            color: #555;
            font-family: sans-serif;
        }
    </style>
</head>

<body>
    <section class="services">
        <div class="services-header">
            <h2>How can we help you?</h2>
            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Suspendisse et <br>justo. Praesent mattis commodo augue.​</p>
            <button class="book-btn">BOOK A MEETING</button>
        </div>
        <div class="services-grid">
            <div class="service-card">
                <i class="far fa-edit"></i>
                <h3>Design</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse et justo.</p>
            </div>
            <div class="service-card">
                <i class="icon">⚙️</i>
                <h3>Development</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse et justo.</p>
            </div>
            <div class="service-card">
                <i class="icon">📣</i>
                <h3>Marketing</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse et justo.</p>
            </div>
            <div class="service-card">
                <i class="icon">💬</i>
                <h3>Social Media</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse et justo.</p>
            </div>
            <div class="service-card">
                <i class="icon">🛒</i>
                <h3>eCommerce</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse et justo.</p>
            </div>
            <div class="service-card">
                <i class="icon">🛟</i>
                <h3>Help & Support</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse et justo.</p>
            </div>
        </div>
    </section>

</body>

</html>









