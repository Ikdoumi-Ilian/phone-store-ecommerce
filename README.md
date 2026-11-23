# Phone Store E-commerce

A smartphone store built with PHP, MySQL and jQuery/AJAX.

Academic project developed during my MIASHS degree at Université Paul-Valéry Montpellier 3, for the web programming course.

**Status — Work in progress**

The project is still being developed as the course progresses. Payment integration is a possible next step. The current checkout records a simulated order.

I started with HTML and CSS pages, then added a database, customer accounts, a shopping cart and orders over the practical assignments. Later exercises introduced AJAX forms and a chat with a basic message filter.

## How it works

PHP handles the pages and communicates with MySQL through PDO. The database stores products, customers, orders, stock reservations and chat messages.

PHP sessions keep track of the logged-in customer and their shopping cart. Adding a product reserves its quantity in the database. Removing it releases the reserved stock.

Before confirming an order, the customer can check the products, quantities, total price and delivery address. The price of each purchased item is saved for the order history.

Registration and login use AJAX. The registration form also checks the fields as the user types and asks the server whether the email address is already registered.

## Main features

- Smartphone catalogue loaded from MySQL
- Product pages with pictures, descriptions, prices and available stock
- Customer registration, login and logout
- Form validation in JavaScript and PHP
- Email availability checks through AJAX
- Session-based shopping cart
- Quantity checks and temporary stock reservations
- Product removal with stock restoration
- Order summary, confirmation and purchase history
- Chat refreshed every three seconds
- Messages limited to 256 characters
- Cleanup of messages older than ten minutes when the chat is read
- Basic automatic message filtering

## Security

The project was also an opportunity to practise security checks in forms, database queries and session management.

- PDO prepared statements bind user-supplied values in SQL queries.
- `htmlspecialchars()` escapes dynamic text displayed in HTML pages.
- jQuery `.text()` displays chat messages and form feedback.
- `password_hash()` hashes passwords during registration.
- `password_verify()` checks passwords during login.
- `isset()` checks that expected request and session variables exist.
- Server-side validation checks required fields, email addresses, password confirmation and positive integer quantities.
- Account actions, cart changes, order confirmation and message submission use POST requests and CSRF tokens.
- Session checks restrict access to the cart, checkout and order history.
- `session_regenerate_id(true)` renews the session identifier after registration and login.
- A unique constraint in the database prevents duplicate customer email addresses.

For this training project, the session keeps the five most recently generated form tokens. This allows several recently opened forms to remain valid while the user moves between pages. Older forms may need to be reloaded once their token has been replaced.

## Message filtering

As part of the practical assignments, I added a basic message filter to practise connecting a Python model to a PHP website.

A Python script processes labelled texts and trains a perceptron with scikit-learn. Its weights and other parameters are exported to JSON files, which PHP reads to calculate a score when someone sends a chat message.

The training data available for this exercise was limited, and the filter makes mistakes. The aim was to understand and implement the whole process, from preparing the data to using the model in the website. This feature was mainly a way to experiment and practise what we learned in class.

## Tech stack

- HTML and CSS for the pages and styling
- PHP for application logic and sessions
- MySQL and PDO for database access
- JavaScript, jQuery and AJAX for forms and chat updates
- Python, NumPy and scikit-learn for the message filter
- JSON for AJAX responses and model parameters
- MAMP and phpMyAdmin for local development

## Note on authorship

The course exercises provided the starting structure and guided the development of the store.

I reused and adapted the functions and examples provided in class, then added my own code to build the pages, connect the features and handle validation.

The project brings together the topics covered during the semester, from database access and session-based cart management to AJAX and message filtering.

## How to run

1. Start Apache and MySQL with MAMP or a similar local environment.
2. Place the project folder in the server's web directory.
3. Create a MySQL database named `phonestore`.
4. Import `phonestore.sql` using phpMyAdmin.
5. Update the database connection settings in `php/bd.php` to match your local host, port, username and password.
6. Enable the PHP `pdo_mysql` and `mbstring` extensions.
7. Open the project through your local server.

For a folder named `phone-store-ecommerce` with Apache running on port 8888, open `http://localhost:8888/phone-store-ecommerce/`.

Adjust the port to match your configuration. The pages load jQuery from a CDN, so an internet connection is needed for the AJAX features.

The exported model files are used directly by PHP. Python is needed if you want to train the filter again.

To run the training script, install the dependencies with `python -m pip install numpy scikit-learn`, then run `python modele/entrainer_filtre.py`.

Training requires the labelled text corpus in `modele/sampled_train/`, its annotations in `modele/annotations_metadata.csv`, and the stopword file in `modele/stopwords.txt`. Access to the training data depends on its distribution terms.

## Repository content

| Path | Purpose |
|---|---|
| `index.php` | Product catalogue and chat interface |
| `articles/` | Individual product pages |
| `php/` | Database access, accounts, cart, orders and chat handlers |
| `styles/` | CSS stylesheet |
| `images/` | Product images |
| `contact/` | Author presentation page |
| `modele/` | Python training script, exported model and stopword file |
| `phonestore.sql` | Database schema and product data |

## Project status

This is an ongoing learning project. Features are added as we cover new topics in class.

The current version includes the shopping flow up to order confirmation. A payment system may be added in a later assignment.

The chat currently accepts guest messages, and stock reservation cleanup runs during page requests. The message filter remains an experimental feature used to practise model integration.

## Author

Ilian Ikdoumi  
MIASHS — Université Paul-Valéry Montpellier 3
