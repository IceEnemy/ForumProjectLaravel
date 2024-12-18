<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>READIT</title>
    <link href="{{ asset('bootstrap-3.4.1.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        html {
            scroll-behavior: smooth;
        }   
        .top-header {
            background-color: rgba(105, 43, 219, 0); 
            color: #fff;
            padding: 10px 40px;
            position: fixed;
            align-items: center;
            top: 0;
            width: 100%;
            z-index: 100;
            display: flex;
            justify-content: space-between;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            box-sizing: border-box;
            transition: background-color 0.3s, padding 0.3s;
        }
        .header-left {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        body{
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            line-height: 1.6;
        }
        .readit{
            position: relative;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(105, 43, 219, 0.4)),
            url('images/pexels-lucasallmann-612892.jpg'); 
            height: 100vh;
            background-attachment: fixed;
            background-position: center;
            background-size: cover;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .text{
            padding: 0 400px;
            text-align: center;
            color: white;
        }
        .container {
            background-color: #692BDB;
            color: white;
            text-align: center;
            padding: 32px 60px;
        }
        .divider {
            margin: 20px auto;
            width: 100%;
            height: 2px;
            background: white;
            opacity: 0.7;
        }
        .button-container {
            display: flex;
            gap: 40px;
            justify-content: center;
        }
        .btn {
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            text-decoration: none;
            background: #692BDB;
            border: none;
            border-radius: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px); /* Slight lift on hover */
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2); /* Enhanced shadow */
        }
        .features {
            background-color: white;
            color: black;
            text-align: center;
            padding: 2rem 0;
        }
        .features h2 {
            margin-bottom: 1.5rem;
        }
        .features .feature {
            display: inline-block;
            width: 30%;
            padding: 1rem;
            margin: 1rem;
        }
        .features .feature .icon {
            font-size: 3rem;
            background: #692BDB;
            color: white;
            width: 60px;
            height: 60px;
            line-height: 60px;
            border-radius: 50%;
            margin: 0 auto 1rem;
        }
        .features .feature .feature-image {
            width: 60px;
            height: auto; 
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="top-header">
        <a class="header-left" href="#">READIT</a>
        <div class="header-right">
            <a href="{{ route('login') }}" style="color: white; margin-right: 20px; text-decoration: none; font-weight: bold;">
                Sign In
            </a>
            Forum for Earth.
        </div>
    </div>
    
    <div class="readit">
        <h1>Welcome to READIT</h1>
        <p class="text">Welcome to READIT, a platform dedicated to uniting voices for climate action and environmental sustainability. Here, we share ideas, solutions, and stories to combat global warming, reduce waste, and protect our planet. Together, we can make a difference—one conversation at a time.</p>
        <div class="button-container">
            <a class="btn" href="#learn-more" role="button">Learn More</a>
            <a class="btn" href="{{ route('register') }}" role="button">Join Us</a>
        </div>
    </div>
    <div class="container" id="learn-more">
        <h1 class="display-4">What is READIT?</h1>
        <p class="lead">At READIT, we believe that every individual has the power to make a difference in the fight against climate change. Our platform is more than just a forum—it's a community where passionate voices come together to share actionable ideas, innovative solutions, and inspiring stories about saving the planet. From discussing ways to reduce carbon footprints and adopt renewable energy, to tackling issues like deforestation, pollution, and sustainable living, READIT provides a space for learning, collaboration, and action. Whether you're an expert in environmental science, a curious learner, or someone who simply wants to do their part, READIT connects you with like-minded individuals who share the same goal: to create a sustainable future for generations to come. Together, we can turn conversations into change and build a world where nature and humanity thrive in harmony.</p>
        <div class="divider"></div>
        <p>Join us and change the world into a better place.</p>
    </div>
    <div class="features">
        <h2>Our Focus</h2>
        <div class="feature">
            <div class="icon">1</div>
            <img src="images/landlife.png" alt="Saving Landlife" class="feature-image">
            <h3>Saving Landlife</h3>
            <p>Protecting animals and ecosystems on land is crucial to maintaining biodiversity and ecological balance. By addressing deforestation, poaching, habitat loss, etc. we can create a safer environment for all species to thrive.</p>
        </div>
        <div class="feature">
            <div class="icon">2</div>
            <img src="images/sealife.png" alt="Saving Sealife" class="feature-image">
            <h3>Saving Sealife</h3>
            <p>Oceans are the lifeblood of our planet, yet marine life faces threats from many things. Our mission is to protect sea creatures and their habitats, ensuring a healthy and thriving underwater world.</p>
        </div>
        <div class="feature">
            <div class="icon">3</div>
            <img src="images/nature.png" alt="Protecting Nature" class="feature-image">
            <h3>Protecting Nature</h3>
            <p>Nature is the foundation of life, providing us with clean air, water, and food. By conserving forests, rivers, and wetlands, we safeguard our planet's beauty and resources for future generations.</p>
        </div>
    </div>
</body>
</html>
