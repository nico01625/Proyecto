<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Profesional</title>

    <link rel="stylesheet" href="css/style.css">

    <!-- ICONOS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

    <!-- SIDEBAR HOLAAAAAAAAAAAAAAAAAAAAAAA JUANJOOOOOOOOOOOOOOOOOOOOOOOOOOOO-->
    <div class="sidebar">

        <div class="logo">
            <i class='bx bxs-store'></i>
            <span>TRAZZIO</span>
        </div>

        <ul class="menu">

            <li class="active">
                <a href="#">
                    <i class='bx bxs-dashboard'></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a href="#">
                    <i class='bx bx-package'></i>
                    <span>Productos</span>
                </a>
            </li>

            <li>
                <a href="#">
                    <i class='bx bx-cart'></i>
                    <span>Ventas</span>
                </a>
            </li>

            <li>
                <a href="#">
                    <i class='bx bx-user'></i>
                    <span>Clientes</span>
                </a>
            </li>

            <li>
                <a href="#">
                    <i class='bx bx-bar-chart'></i>
                    <span>Reportes</span>
                </a>
            </li>

            <li>
                <a href="#">
                    <i class='bx bx-cog'></i>
                    <span>Configuración</span>
                </a>
            </li>

        </ul>

    </div>

    <!-- MAIN -->
    <div class="main-content">

        <!-- NAVBAR -->
        <div class="navbar">

            <div class="search-box">
                <i class='bx bx-search'></i>
                <input type="text" placeholder="Buscar...">
            </div>

            <div class="profile">
                <img src="https://i.pravatar.cc/40" alt="">
                <span>Administrador</span>
            </div>

        </div>

        <!-- CARDS -->
        <div class="cards">

            <div class="card">
                <div>
                    <h1>$25K</h1>
                    <p>Ventas Totales</p>
                </div>

                <i class='bx bx-dollar-circle'></i>
            </div>

            <div class="card">
                <div>
                    <h1>1,240</h1>
                    <p>Productos</p>
                </div>

                <i class='bx bx-package'></i>
            </div>

            <div class="card">
                <div>
                    <h1>320</h1>
                    <p>Clientes</p>
                </div>

                <i class='bx bx-user'></i>
            </div>

            <div class="card">
                <div>
                    <h1>85%</h1>
                    <p>Ganancias</p>
                </div>

                <i class='bx bx-line-chart'></i>
            </div>

        </div>

        <!-- TABLA -->
        <div class="table-container">

            <div class="table-header">
                <h2>Productos Recientes</h2>
                <button>Agregar</button>
            </div>

            <table>

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td>#001</td>
                        <td>Cuaderno</td>
                        <td>$12.000</td>
                        <td><span class="status active">Disponible</span></td>
                    </tr>

                    <tr>
                        <td>#002</td>
                        <td>Lapicero</td>
                        <td>$3.500</td>
                        <td><span class="status inactive">Agotado</span></td>
                    </tr>

                    <tr>
                        <td>#003</td>
                        <td>Marcadores</td>
                        <td>$8.000</td>
                        <td><span class="status active">Disponible</span></td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</body>
</html>