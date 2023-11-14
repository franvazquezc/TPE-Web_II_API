<?php
require_once 'config.php'; 

class Model {
  protected $db;

  public function __construct() {
    $this->db = new PDO("mysql:host=" . MYSQL_HOST . ";dbname=" . MYSQL_DB . ";charset=utf8", MYSQL_USER, MYSQL_PASS);
    $this->deploy();
  }
  private function deploy() {
    $query = $this->db->query('SHOW TABLES');
    $tables = $query->fetchAll();
    if(count($tables) == 0) {
      $sql = <<<END
      --
      -- Estructura de tabla para la tabla `categories`
      --

      CREATE TABLE `categories` (
        `name` varchar(45) NOT NULL,
        `category_id` int(11) NOT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

      --
      -- Volcado de datos para la tabla `categories`
      --

      INSERT INTO `categories` (`name`, `category_id`) VALUES
      ('Categoría 1', 1),
      ('Categoría 2', 2);

      -- --------------------------------------------------------

      --
      -- Estructura de tabla para la tabla `products`
      --

      CREATE TABLE `products` (
        `product_id` int(11) NOT NULL,
        `name` varchar(45) NOT NULL,
        `description` text NOT NULL,
        `price` double NOT NULL,
        `stock` int(11) NOT NULL,
        `category_id` int(11) NOT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

      --
      -- Volcado de datos para la tabla `products`
      --

      INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `stock`, `category_id`) VALUES
      (8, 'Producto 1', '...', 1400, 145, 2),
      (13, 'Producto 2', 'Descripción del producto 2.', 140, 15, 2),
      (18, 'Producto 3', 'Descripción del producto 3.', 300, 15, 2),
      (20, 'Producto 4', '...', 200, 10, 2),
      (21, 'Producto 5 Modif', 'Descripción del producto 5', 1, 1, 1);

      -- --------------------------------------------------------

      --
      -- Estructura de tabla para la tabla `users`
      --

      CREATE TABLE `users` (
        `user_id` int(11) NOT NULL,
        `user_name` varchar(45) NOT NULL,
        `password` varchar(255) NOT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

      --
      -- Volcado de datos para la tabla `users`
      --

      INSERT INTO `users` (`user_id`, `user_name`, `password`) VALUES
      (1, 'webadmin', '$2a$12$Mzp6twU/EDdYV5ThsaRSmOnokunKQfgT5wvqhsYbsVKg8SYXZnpNW');

      --
      -- Índices para tablas volcadas
      --

      --
      -- Indices de la tabla `categories`
      --
      ALTER TABLE `categories`
        ADD PRIMARY KEY (`category_id`);

      --
      -- Indices de la tabla `products`
      --
      ALTER TABLE `products`
        ADD PRIMARY KEY (`product_id`),
        ADD KEY `FK_id_categoria` (`category_id`);

      --
      -- Indices de la tabla `users`
      --
      ALTER TABLE `users`
        ADD PRIMARY KEY (`user_id`);

      --
      -- AUTO_INCREMENT de las tablas volcadas
      --

      --
      -- AUTO_INCREMENT de la tabla `categories`
      --
      ALTER TABLE `categories`
        MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

      --
      -- AUTO_INCREMENT de la tabla `products`
      --
      ALTER TABLE `products`
        MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

      --
      -- AUTO_INCREMENT de la tabla `users`
      --
      ALTER TABLE `users`
        MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

      --
      -- Restricciones para tablas volcadas
      --

      --
      -- Filtros para la tabla `products`
      --
      ALTER TABLE `products`
        ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);
      COMMIT;

      /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
      /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
      /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
    END;
    $this->db->query($sql);
    }
  }
}