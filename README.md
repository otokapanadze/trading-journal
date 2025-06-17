# Trading Journal - Project Overview

This is a comprehensive trading journal application built with Laravel and the FilamentPHP admin panel. It allows traders to meticulously log, review, and analyze their trading performance across multiple accounts and strategies. The application is designed to be context-aware, focusing on the user's currently selected trading account for a streamlined experience.

<img width="628" alt="Screenshot 2025-06-17 at 7 14 26 PM" src="https://github.com/user-attachments/assets/1a29d603-ca39-4709-93c3-70e432335433" />

## Core Features

The application is structured around several key resources, each providing a distinct set of functionalities.

### 📈 Account Management

<img width="946" alt="Screenshot 2025-06-17 at 7 18 02 PM" src="https://github.com/user-attachments/assets/166d0530-1012-4a92-afe8-843bb94aa912" />

The `Account` resource is the central hub for managing different trading portfolios.

-   **Multi-Account Support:** Create and manage multiple trading accounts, each with its own name, description, and initial balance.
-   **Dynamic Balance Tracking:** The system automatically calculates the `Current Balance` for each account by summing the initial balance with the profit and loss (P/L) of all its associated trades.
-   **Context Switching:** Users can set any account as their "Current Account". All other resources, like the Trade and Session lists, will then be filtered to show data only for this active account.
-   **Defaults for Efficiency:** Assign a default trading symbol (e.g., `BTC/USD`) and default strategies to each account to speed up the process of logging new trades.
-   **Pinning:** Pin important accounts to the top of the list for quick access.
-   **Performance Analytics:** The account view page provides a detailed dashboard with key performance indicators (KPIs) including:
    -   Current vs. Initial Balance
    -   Total Number of Trades
    -   Winning vs. Losing Trades
    -   Win/Loss Ratio (%)
    -   Maximum Drawdown (%)
-   **Data Import:** A dedicated page allows for importing trade data into an account.

### 📊 Trade Logging

<img width="944" alt="Screenshot 2025-06-17 at 7 14 57 PM" src="https://github.com/user-attachments/assets/cdba038d-e0c3-4216-80da-49b5c9be69d2" />

The `Trade` resource is the heart of the journal, capturing the details of each individual trade.

-   **Detailed Trade Entry:** Log trades with essential details:
    -   **Symbol:** The asset that was traded.
    -   **Direction:** `Buy 📈` or `Sell 📉`.
    -   **P/L:** The numeric profit or loss for the trade.
-   **Rich Contextual Information:**
    -   **Notes:** A rich-text editor to write detailed thoughts, analysis, or reasons for the trade.
    -   **Image Attachments:** Attach multiple images (via URL) to each trade, perfect for saving chart setups, entry points, and exit points.
    -   **Strategy Tagging:** Tag each trade with one or more predefined strategies to track their performance.
-   **Automatic Association:** New trades are automatically associated with the user's currently active account.
-   **Timestamps:** Record the `open_at` and `closes_at` times for each trade.
-   **Intuitive Lists:** The trade list provides a quick, color-coded overview of P/L and trade direction.

### 💡 Strategy Documentation

<img width="934" alt="Screenshot 2025-06-17 at 7 17 00 PM" src="https://github.com/user-attachments/assets/4526a5f1-27d2-473a-a922-5a362102daee" />

The `Strategy` resource allows users to build a library of their trading methods.

-   **Define Strategies:** Create and define personal trading strategies with a name.
-   **Detailed Descriptions:** Use a rich-text editor to write comprehensive descriptions, rules, and conditions for each strategy.
-   **Visual Examples:** Add multiple example images to visually illustrate the ideal chart patterns or setups for a strategy.
-   **Easy Linking:** These strategies can be linked to accounts (as defaults) and individual trades (for performance tracking).

### 🎬 Trading Sessions

<img width="951" alt="Screenshot 2025-06-17 at 7 17 53 PM" src="https://github.com/user-attachments/assets/560b585c-f4aa-4985-b47d-1b2d8af033e5" />

The `Session` resource allows for grouping trades that occurred within a specific period.

-   **Group Trades:** Create sessions with a name (e.g., "London Session - 2023-10-27"), start time, and end time.
-   **Video Review:** Upload a video recording of the trading session for in-depth review and analysis.
-   **Session-Specific Trades:** The session view page displays a list of all trades that have been associated with it.

### 🔣 Symbol Management

<img width="933" alt="Screenshot 2025-06-17 at 7 15 59 PM" src="https://github.com/user-attachments/assets/58a084db-c2d4-42e9-897b-b285e38bffc2" />

The `Symbol` resource is a simple utility for managing tradable assets.

-   **CRUD Interface:** A simple interface to create, read, update, and delete trading symbols (e.g., `AAPL`, `BTC/USD`, `EUR/JPY`).
-   **Application-Wide Use:** These symbols are then available in a searchable dropdown when setting up accounts or logging new trades.

## Technical Stack

-   **Backend:** Laravel
-   **Admin Panel / UI:** FilamentPHP v3
-   **Database:** (Not specified, but compatible with Laravel's Eloquent ORM)
