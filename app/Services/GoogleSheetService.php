<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;

class GoogleSheetService
{
    protected $client;
    protected $service;
    protected $spreadsheetId;

    // $range = "Sheet1!A{$rowNumber}:E{$rowNumber}"; // Adjust columns and row number

    public function __construct()
    {
        $this->spreadsheetId = config('services.google.sheet_id');

        $this->client = new Client();
        $this->client->setApplicationName('Laravel Google Sheets Integration');
        $this->client->setScopes([Sheets::SPREADSHEETS]);
        $this->client->setAuthConfig(base_path(config('services.google.service_account_json')));
        $this->service = new Sheets($this->client);
    }

    /**
     * Read data from a specific range in the sheet.
     */
    public function readSheet(string $range): array
    {
        $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $range);
        return $response->getValues() ?? [];
    }

    public function getRowByCellValue(string $sheetName, string $columnLetter, string $value)
    {
        // Fetch all data from the sheet
        $range = $sheetName;
        $data = $this->readSheet($range);

        // Identify the column index based on the column letter
        $columnIndex = ord(strtoupper($columnLetter)) - ord('A');

        // Search for the row where the cell in the specified column matches the value
        foreach ($data as $row) {
            if (isset($row[$columnIndex]) && $row[$columnIndex] == $value) {
                return $row; // Return the entire row
            }
        }

        // Return null if not found
        return null;
    }


    /**
     * Append a row to the sheet.
     */
    public function appendRow(string $range, array $values)
    {
        $body = new Sheets\ValueRange(['values' => [$values]]);
        $params = ['valueInputOption' => 'RAW'];
        return $this->service->spreadsheets_values->append($this->spreadsheetId, $range, $body, $params);
    }

    /**
     * Update a specific range in the sheet.
     */
    public function updateCell(string $range, array $values)
    {
        $body = new Sheets\ValueRange(['values' => [$values]]);
        $params = ['valueInputOption' => 'RAW'];
        return $this->service->spreadsheets_values->update($this->spreadsheetId, $range, $body, $params);
    }
}
