<style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .header {
            background-color: #333;
            color: white;
            padding: 10px 0;
            position: relative;
        }

        .header .container {
            width: 90%;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header .container .username {
            font-size: 16px;
        }

        .header .container .logout a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            background-color: #007bff;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .header .container .logout a:hover {
            background-color: #0056b3;
        }

        .content {
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .delete-button,
        .reset-password-button {
            display: inline-block;
            padding: 8px 16px;
            background-color: #ff4d4d;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .reset-password-button {
            background-color: #0080FF;
        }

        .delete-button:hover,
        .reset-password-button:hover {
            background-color: #e60000;
        }

        .reset-password-button:hover {
            background-color: #0056b3;
        }
    </style>