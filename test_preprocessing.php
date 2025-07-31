<?php
/**
 * CSV File Splitter Utility
 * 
 * This script helps split large CSV files into smaller chunks for easier import
 * Usage: php test_preprocessing.php input.csv output_prefix chunk_size
 */

if ($argc < 4) {
    echo "Usage: php test_preprocessing.php <input_file> <output_prefix> <chunk_size>\n";
    echo "Example: php test_preprocessing.php large_file.csv chunk_ 5000\n";
    exit(1);
}

$input_file = $argv[1];
$output_prefix = $argv[2];
$chunk_size = (int)$argv[3];

if (!file_exists($input_file)) {
    echo "Error: Input file '$input_file' not found.\n";
    exit(1);
}

if ($chunk_size < 100) {
    echo "Error: Chunk size must be at least 100.\n";
    exit(1);
}

echo "Starting CSV file splitter...\n";
echo "Input file: $input_file\n";
echo "Output prefix: $output_prefix\n";
echo "Chunk size: $chunk_size\n\n";

try {
    $handle = fopen($input_file, 'r');
    if (!$handle) {
        throw new Exception("Cannot open input file");
    }
    
    // Read headers
    $headers = fgetcsv($handle);
    if (!$headers) {
        throw new Exception("Cannot read CSV headers");
    }
    
    echo "Headers found: " . implode(', ', $headers) . "\n\n";
    
    $chunk_number = 1;
    $line_count = 0;
    $current_chunk = array();
    $total_processed = 0;
    
    while (($row = fgetcsv($handle)) !== false) {
        $current_chunk[] = $row;
        $line_count++;
        
        if ($line_count >= $chunk_size) {
            $output_file = $output_prefix . sprintf('%03d', $chunk_number) . '.csv';
            
            $output_handle = fopen($output_file, 'w');
            if (!$output_handle) {
                throw new Exception("Cannot create output file: $output_file");
            }
            
            // Write headers
            fputcsv($output_handle, $headers);
            
            // Write chunk data
            foreach ($current_chunk as $row_data) {
                fputcsv($output_handle, $row_data);
            }
            
            fclose($output_handle);
            
            echo "Created chunk $chunk_number: $output_file ($line_count records)\n";
            
            $chunk_number++;
            $line_count = 0;
            $current_chunk = array();
            $total_processed += $line_count;
        }
    }
    
    // Write remaining data
    if (!empty($current_chunk)) {
        $output_file = $output_prefix . sprintf('%03d', $chunk_number) . '.csv';
        
        $output_handle = fopen($output_file, 'w');
        if (!$output_handle) {
            throw new Exception("Cannot create output file: $output_file");
        }
        
        // Write headers
        fputcsv($output_handle, $headers);
        
        // Write remaining data
        foreach ($current_chunk as $row_data) {
            fputcsv($output_handle, $row_data);
        }
        
        fclose($output_handle);
        
        echo "Created final chunk $chunk_number: $output_file (" . count($current_chunk) . " records)\n";
    }
    
    fclose($handle);
    
    echo "\nFile splitting completed successfully!\n";
    echo "Total chunks created: " . ($chunk_number - 1) . "\n";
    echo "You can now import each chunk separately using the WordPress admin panel.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?> 