
# **Oiko API - v1.0**

## **Overview**
The Oiko API provides endpoints for managing user accounts, transactions, categories, goals, notifications, and dashboard summaries. It is designed to help users track their finances effectively.

## **Authentication**
The API uses authentication via `Sanctum`. To access protected routes, include the token in the request header:

**Header:**
```
Authorization: Bearer {token}
Accept: application/json
```

---

## **Endpoints**

### **Base URL**
```
http://dominio.com/api
```

### **Authentication**
#### **Login**
- **Method:** POST `/login`
- **Description:** Logs in the user.
- **Parameters:**
  - `email` (string, required)
  - `password` (string, required)
- **Response:**
```json
{
  "token": "string"
}
```

#### **Register**
- **Method:** POST `/register`
- **Description:** Registers a new user.
- **Parameters:**
    - `name` (string, required)
    - `email` (string, required)
    - `password` (string, required)
- **Response:**
```json
{
  "message": "User successfully registered"
}
```

#### **Logout**
- **Method:** POST `/logout`
- **Description:** Ends the authenticated user's session.
- **Response:**
```json
{
  "message": "Logout successful"
}
```

---

### **User**
#### **Authentication Test**
- **Method:** GET `/test`
- **Description:** Checks if the user is authenticated.
- **Response:**
```json
{
  "message": "User logged in"
}
```

---

### **Transactions**
#### **List Transactions**
- **Method:** GET `/user/transactions`
- **Description:** Returns a list of the authenticated user's transactions.
- **Optional Parameters:**
    - `search` (string)
    - `category` (integer)
    - `type` (string: `income`, `expense`, `transfer`)
    - `start_date` (date)
    - `end_date` (date)
- **Response:**
```json
{
    "data": [
        {
            "id": 1,
            "category_id": 7,
            "category_name": "Moradia",
            "amount": "150.00",
            "type": "expense",
            "description": "Gastei muito com isso",
            "date": "2025-04-19 11:00:00"
        }
    ],
    "links": {
        "first": "http://dominio.com/api/user/transactions?page=1",
        "last": "http://dominio.com/api/user/transactions?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://dominio.com/api/user/transactions?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "http://dominio.com/api/user/transactions",
        "per_page": 10,
        "to": 1,
        "total": 1
    }
}
```

#### **Create Transaction**
- **Method:** POST `/user/transactions`
- **Description:** Creates a new transaction.
- **Parameters:**
    - `type` (string, required) 
        - `income`
        - `expense`
        - `transfer`
    - `category_id` (integer, required) - should be a valid category ID listed in the categories endpoint
    - `description` (string, required)
    - `amount` (decimal, required)
    - `date` (date, optional)
- **Response:**
```json
{
    "data": {
        "id": 1,
        "category_id": "7",
        "amount": "150.00",
        "type": "expense",
        "description": "Gastei muito com isso",
        "date": "2025-04-19 11:00:00"
    }
}
```

#### **Update Transaction**
- **Method:** PUT `/user/transactions/{id}`
- **Description:** Updates an existing transaction.
- **Parameters:** Same as creation.
- **Response:**
```json
{
    "data": {
        "id": 1,
        "category_id": "7",
        "amount": "200.00",
        "type": "expense",
        "description": "Nooossaaa",
        "date": "2025-04-20 11:00:00"
    }
}
```

#### **Delete Transaction**
- **Method:** DELETE `/user/transactions/{id}`
- **Description:** Deletes a transaction.
- **Response:**
```json
{
  "message": "Transaction deleted successfully"
}
```

#### **Show Transaction**
- **Method:** GET `/user/transactions/{id}`
- **Description:** Returns a specific transaction.
- **Response:**
```json
{
    "data": {
        "id": 3,
        "category_id": 7,
        "category_name": "Moradia",
        "amount": "150.00",
        "type": "expense",
        "description": "Gastei muito com isso",
        "date": "2025-04-19 11:00:00"
    }
}
```

#### **Total by Expense Category**
- **Method:** GET `/user/transactions/total/by-expense`
- **Description:** Returns the total expenses by category.
- **Parameters:**
    - `start_date` (date, required)
    - `end_date` (date, required)
- **Response:**
```json
{
    "data": [
        {
            "category": "Moradia",
            "total": "150.00",
            "percentage": 100
        }
    ],
    "total": 150
}
```

---

### **Categories**
#### **List Categories**
- **Method:** GET `/user/categories`
- **Description:** Returns a list of categories.
- **Response:**
```json
{
  "data": [
    {
        "id": 1,
        "name": "Salário",
        "type": "income",
        "color": "#00C853",
        "user_id": null,
        "is_system": 1
    },
    {
      "id": 2,
      "name": "Bônus",
      "type": "income",
      "color": "#2962FF",
      "user_id": null,
      "is_system": 1
    }
  ]
}
```

#### ** Show Category**
- **Method:** GET `/user/categories/{id}`
- **Description:** Returns a specific category.
- **Response:**
```json
{
    "data": {
        "id": 12,
        "name": "Contas",
        "type": "expense",
        "color": "#607D8B",
        "user_id": null,
        "is_system": 1
    }
}
```

#### **Create Category**
- **Method:** POST `/user/categories`
- **Description:** Creates a new category.
- **Parameters:**
    - `name` (string, required)
    - `type` (string: `income`, `expense`, required)
    - `color` (string, required)
- **Response:**
```json
{
    "data": {
        "id": 14,
        "name": "Lembrancinha",
        "type": "expense",
        "color": "#12D600",
        "user_id": 1,
        "is_system": null
    }
}
```

#### **Update Category**
- **Method:** PUT `/user/categories/{id}`
- **Description:** Updates an existing category.
- **Parameters:**
    - `name` (string, required)
    - `type` (string: `income`, `expense`, required)
    - `color` (string, required)
- **Response:**
```json
{
    "data": {
        "id": 14,
        "name": "Lembrancinha",
        "type": "expense",
        "color": "#12D600",
        "user_id": 1,
        "is_system": null
    }
}
```

#### **Delete Category**
- **Method:** DELETE `/user/categories/{id}`
- **Description:** Deletes a category.
- **Response:**
```json
{
    "message": "Category deleted successfully"
}
```

---

### **Goals**
#### **List Goals**
- **Method:** GET `/user/goals`
- **Description:** Returns a list of the user's goals.
- **Response:**
```json
{
    "data": [
        {
            "id": 1,
            "user_id": 1,
            "category_id": 6,
            "category_name": "Transporte",
            "target_amount": "500.00",
            "frequency": "weekly",
            "start_date": "2025-04-17",
            "end_date": null,
            "progress": 0
        }
    ]
}
```

#### **Create Goal**
- **Method:** POST `/user/goals`
- **Description:** Creates a new goal.
- **Parameters:**
    - `category_id` (integer, required)
    - `target_amount` (decimal, required)
    - `frequency` (string: `weekly`, `monthly`, required)
    - `description` (string, optional)
    - `start_date` (date, required)
    - `end_date` (date, optional)
- **Response:**
```json
{
    "user_id": 1,
    "category_id": "6",
    "target_amount": "500",
    "frequency": "weekly",
    "start_date": "2025-04-17",
    "end_date": null,
    "id": 1
}
```

#### **Update Goal**
- **Method:** PUT `/user/goals/{id}`
- **Description:** Updates an existing goal.
- **Parameters:** Same as creation.
- **Response:**
```json
{
    "id": 1,
    "user_id": 1,
    "category_id": 6,
    "target_amount": "600",
    "frequency": "weekly",
    "start_date": "2025-04-17",
    "end_date": null
}
```

#### **Delete Goal**
- **Method:** DELETE `/user/goals/{id}`
- **Description:** Deletes a goal.
- **Response:**
```json
{
    "message": "Goal deleted"
}
```

#### **Goal Progress**
- **Method:** GET `/user/goals/{goal}/progress`
- **Description:** Returns the progress of a goal.
- **Response:**
```json
[
    {
        "id": 2,
        "goal_id": 2,
        "progress_amount": "0.00",
        "notified_threshold": 0,
        "period_start": "2025-04-01",
        "period_end": "2025-04-30"
    }
]
```

---

### **Notifications**
#### **List Notifications**
- **Method:** GET `/user/notifications`
- **Description:** Returns the user's notifications.
- **Response:**
```json
{
    "data": [
        {
            "id": "b3ffa1d4-0dd1-4203-afac-95578326f943",
            "type": "GoalThresholdNotification",
            "data": {
                "goal_id": 3,
                "category_id": 6,
                "target_amount": 1500,
                "current_amount": 1000,
                "percentage": 66,
                "message": "Você atingiu 66% da sua meta."
            },
            "read_at": null,
            "created_at": "2025-04-24 22:37:39"
        }
    ],
    "links": {
        "first": "http://localhost/api/user/notifications?page=1",
        "last": "http://localhost/api/user/notifications?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://localhost/api/user/notifications?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "http://localhost/api/user/notifications",
        "per_page": 20,
        "to": 1,
        "total": 1
    }
}
```

#### **Mark Notification as Read**
- **Method:** POST `/user/notifications/{uuid}/read`
- **Description:** Marks a notification as read.
- **Response:**
```json
{
    "status": "ok"
}
```

#### **Mark All as Read**
- **Method:** POST `/user/notifications/read-all`
- **Description:** Marks all notifications as read.
- **Response:**
```json
{
    "status": "ok"
}
```

---

### **Dashboard**
#### **Dashboard Summary**
- **Method:** GET `/user/dashboard`
- **Description:** Returns a summary with dashboard information.
- **Response:** *(Example)*
```json
{
    "data": {
        "total_income": "0.00",
        "total_expense": "2500.00",
        "total_transfer": "0.00",
        "balance": "-2500.00"
    }
}
```
