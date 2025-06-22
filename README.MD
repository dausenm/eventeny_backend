# Eventeny Take-Home Project — Backend

This repository contains the PHP REST API backend for the Eventeny Take-Home Project. It connects to a MySQL database hosted on Railway and provides full API support for the Flutter mobile frontend.

---

## Tech Stack

- PHP (REST API)
- MySQL database (hosted on Railway)
- Railway platform for deployment & hosting

---

## API Endpoints

### `GET /get_events.php`

- Returns a list of all upcoming events.

### `GET /get_tickets.php?event_id={id}`

- Returns all available ticket types for a given event.

### `POST /submit_order.php`

- Submits a purchase request.
- Validates ticket availability before committing the purchase.
- Prevents overselling by using database transactions and quantity checks.

---

## Database Schema

The backend operates on the following tables:

- `events (id, name, date, location, description, image_url)`
- `tickets (id, event_id, type, price, quantity_available)`
- `purchases (id, name, email, created_at)`
- `purchase_items (id, purchase_id, ticket_id, event_id, quantity, price)`

**Note:**  
The `event_id` column inside `purchase_items` is not used by the backend, but remains due to limitations of Railway's table editing functionality.

---

## Deployment

- Hosted on Railway.
- All database credentials are securely managed via Railway environment variables.
- API service is publicly available and consumed directly by the Flutter frontend.

---

## Known Limitations

- No authentication or real payment processing implemented (for demo purposes).
- Database tables were manually created due to Railway's lack of schema migration support.
- Production version would leverage stronger hosting infrastructure and more advanced backend frameworks.

---

## Related Repository

The full Flutter frontend for this backend lives here:  
[Frontend Repository (Flutter App)](https://github.com/dausenm/eventeny_app)

---

## Contact

- Dausen Mason  
- dausenmason@yahoo.com  
- https://www.github.com/dausenm  
- https://www.linkedin.com/in/dausenmason

