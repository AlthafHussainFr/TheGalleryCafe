<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <style>
/* CSS VARIABLES */
:root {
  --primary: #ddd;
  --dark:  rgb(75, 44, 11);
  --light: #fff;
  --shadow: 0 1px 5px rgba(104, 104, 104, 0.8);
}

html {
  box-sizing: border-box;
  font-family: 'Montserrat', sans-serif;
  color: var(--dark);
}

body {
  background: #ccc;
  margin: 30px 50px;
  line-height: 1.5;
}

.btn {
  background: var(--dark);
  color: var(--light);
  padding: 0.6rem 1.3rem;
  text-decoration: none;
  border: 0;
}

img {
  max-width: 100%;
}

.wrapper {
  display: grid;
  grid-gap: 20px;
}

/* Navigation */
.main-nav ul {
  display: grid;
  grid-gap: 20px;
  padding: 0;
  list-style: none;
  grid-template-columns: repeat(4, 1fr);
}

.main-nav a {
  background: var(--primary);
  display: block;
  text-decoration: none;
  color: var(--dark);
  padding: 0.8rem;
  font-size: 1.1rem;
  text-align: center;
  text-transform: uppercase;
  box-shadow: var(--shadow);
}

.main-nav a:hover {
  background: var(--dark);
  color: var(--light);
}

/* Top Container */
.top-container {
  display: grid;
  grid-gap: 20px;
  grid-template-areas:
    "showcase showcase top-box-a"
    "showcase showcase top-box-b";
}

.showcase {
  grid-area: showcase;
  min-height: 400px;
  background: url("sign.jpg");
  background-size: cover;
  padding: 3rem;
  display: flex;
  flex-direction: column;
  align-items: start;
  justify-content: center;
  box-shadow: var(--shadow);
}

.showcase h1 {
  font-size: 3.5rem;
  margin-bottom: 0;
  color: var(--light);
}

.showcase p {
  font-size: 1.1rem;
  margin-top: 0;
  color: var(--light);
}

/* Top boxes */
.top-box {
  background: var(--primary);
  display: grid;
  justify-items: center;
  align-items: center;
  padding: 1.5rem;
  box-shadow: var(--shadow);
}

.top-box .price {
  font-size: 2rem;
}

.top-box-a {
  grid-area: top-box-a;
}

.top-box-b {
  grid-area: top-box-b;
}

.boxes {
  display: grid;
  grid-gap: 20px;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
}

.box {
  background: var(--primary);
  padding: 1.5rem;
  text-align: center;
  box-shadow: var(--shadow);
}

/* Info */
.info {
  background: var(--primary);
  box-shadow: var(--shadow);
  display: grid;
  grid-gap: 30px;
  grid-template-columns: repeat(2, 1fr);
  padding: 3rem;
}

/* Portfolio */
.portfolio {
  display: grid;
  grid-gap: 20px;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
}

.portfolio img {
  width: 100%;
  box-shadow: var(--shadow);
}

/* Footer */
footer {
  margin-top: 2rem;
  background: var(--dark);
  color: var(--light);
  text-align: center;
  padding: 1rem;
}

/* Media queries */
@media (max-width: 700px) {
  .top-container {
    grid-template-areas: 
      "showcase showcase"
      "top-box-a top-box-b";
  }
  
  .showcase h1 {
    font-size: 2.5rem;
  }
  
  .showcase p {
    font-size: 0.9rem;
  }
  
  .top-box {
    padding: 1.2rem;
  }
  
  .top-box .price {
    font-size: 1.5rem;
  }
  
  .main-nav ul {
    grid-template-columns: 1fr;
  }
  
  .info {
    grid-template-columns: 1fr;
  }
  
  .info .btn {
    display: block;
    text-align: center;
  }
}
@media (max-width: 500px) {
  .top-container {
    grid-template-areas: 
      "showcase"
      "top-box-a"
      "top-box-b";
  }
}

@media (max-width: 375px) {
  body {
    margin: 10px 20px;
  }
}
    </style>
    <script defer src="https://use.fontawesome.com/releases/v5.2.0/js/all.js" integrity="sha384-4oV5EgaV02iISL2ban6c/RmotsABqE4yZxZLcYMAdG7FAPsyHYAPpywE9PJo+Khy" crossorigin="anonymous"></script>


<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">

<div class="wrapper">
  <!-- Navigation -->
  <nav class="main-nav">
    <ul>
      <li><a href="index.html">Logout</a></li>
      
 
    </ul>
  </nav>
  
    <!-- Top container -->
  <section class="top-container">
    <header class="showcase">
     
    </header>
    <div class="top-box top-box-a">
      <h4> Orders</h4>
  
      <a class="btn" href="view.php">Manage Orders</a>
    </div>
    
  

  <!-- Footer -->
  <footer>
    <p>The Gallery Cafe &copy; @024</p>
  </footer>
</div>
<!-- Wrapper End -->
</body>
</html>