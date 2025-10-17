<?php

namespace App\Support;

use Illuminate\Support\Carbon;
use RuntimeException;
use ZipArchive;

class SimpleXlsxExporter
{
    public static function build(array $rows, string $sheetName = 'Sheet1'): string
    {
        $sheetName = self::sanitizeSheetName($sheetName);

        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx');

        if ($tempFile === false) {
            throw new RuntimeException('Unable to create temporary file for XLSX export.');
        }

        $zip = new ZipArchive();

        if ($zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException('Unable to open XLSX archive for writing.');
        }

        $timestamp = Carbon::now('UTC')->format('Y-m-d\TH:i:s\Z');

        $zip->addFromString('[Content_Types].xml', self::contentTypes());
        $zip->addFromString('_rels/.rels', self::rootRels());
        $zip->addFromString('docProps/app.xml', self::appProperties());
        $zip->addFromString('docProps/core.xml', self::coreProperties($timestamp));
        $zip->addFromString('xl/_rels/workbook.xml.rels', self::workbookRels());
        $zip->addFromString('xl/workbook.xml', self::workbook($sheetName));
        $zip->addFromString('xl/styles.xml', self::styles());
        $zip->addFromString('xl/worksheets/sheet1.xml', self::worksheet($rows));

        $zip->close();

        $contents = file_get_contents($tempFile);
        @unlink($tempFile);

        if ($contents === false) {
            throw new RuntimeException('Unable to read generated XLSX file.');
        }

        return $contents;
    }

    protected static function contentTypes(): string
    {
        return <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
    <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
    <Default Extension="xml" ContentType="application/xml"/>
    <Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
    <Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
    <Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>
    <Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml"/>
    <Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/>
</Types>
XML;
    }

    protected static function rootRels(): string
    {
        return <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
    <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml"/>
    <Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties" Target="docProps/app.xml"/>
</Relationships>
XML;
    }

    protected static function appProperties(): string
    {
        return <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties" xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">
    <Application>Laravel</Application>
</Properties>
XML;
    }

    protected static function coreProperties(string $timestamp): string
    {
        return <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:dcmitype="http://purl.org/dc/dcmitype/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <dc:creator>URSBid</dc:creator>
    <cp:lastModifiedBy>URSBid</cp:lastModifiedBy>
    <dcterms:created xsi:type="dcterms:W3CDTF">{$timestamp}</dcterms:created>
    <dcterms:modified xsi:type="dcterms:W3CDTF">{$timestamp}</dcterms:modified>
</cp:coreProperties>
XML;
    }

    protected static function workbookRels(): string
    {
        return <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
    <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
</Relationships>
XML;
    }

    protected static function workbook(string $sheetName): string
    {
        $escapedSheetName = self::escape($sheetName);

        return <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
    <sheets>
        <sheet name="{$escapedSheetName}" sheetId="1" r:id="rId1"/>
    </sheets>
</workbook>
XML;
    }

    protected static function styles(): string
    {
        return <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
    <fonts count="1">
        <font>
            <sz val="11"/>
            <color theme="1"/>
            <name val="Calibri"/>
            <family val="2"/>
        </font>
    </fonts>
    <fills count="1">
        <fill>
            <patternFill patternType="none"/>
        </fill>
    </fills>
    <borders count="1">
        <border>
            <left/>
            <right/>
            <top/>
            <bottom/>
            <diagonal/>
        </border>
    </borders>
    <cellStyleXfs count="1">
        <xf numFmtId="0" fontId="0" fillId="0" borderId="0"/>
    </cellStyleXfs>
    <cellXfs count="1">
        <xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>
    </cellXfs>
    <cellStyles count="1">
        <cellStyle name="Normal" xfId="0" builtinId="0"/>
    </cellStyles>
</styleSheet>
XML;
    }

    protected static function worksheet(array $rows): string
    {
        $sheetData = '<sheetData>';
        $rowIndex = 1;

        foreach ($rows as $row) {
            $sheetData .= '<row r="' . $rowIndex . '">';
            $rowValues = array_values($row);
            $columnCount = count($rowValues);

            for ($columnIndex = 0; $columnIndex < $columnCount; $columnIndex++) {
                $value = $rowValues[$columnIndex];
                $cellReference = self::columnLetter($columnIndex) . $rowIndex;

                if ($value === null || $value === '') {
                    $sheetData .= '<c r="' . $cellReference . '"/>';
                    continue;
                }

                if (is_numeric($value)) {
                    $sheetData .= '<c r="' . $cellReference . '"><v>' . self::sanitizeNumeric($value) . '</v></c>';
                    continue;
                }

                $sheetData .= '<c r="' . $cellReference . '" t="inlineStr"><is><t>' . self::escape((string) $value) . '</t></is></c>';
            }

            $sheetData .= '</row>';
            $rowIndex++;
        }

        $sheetData .= '</sheetData>';

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" '
            . 'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . $sheetData
            . '</worksheet>';
    }

    protected static function columnLetter(int $index): string
    {
        $letter = '';
        $index++;

        while ($index > 0) {
            $remainder = ($index - 1) % 26;
            $letter = chr(65 + $remainder) . $letter;
            $index = intdiv($index - 1, 26);
        }

        return $letter;
    }

    protected static function sanitizeNumeric($value): string
    {
        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        return (string) (float) $value;
    }

    protected static function escape(string $value): string
    {
        $sanitized = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/u', '', $value);
        if ($sanitized === null) {
            $sanitized = $value;
        }

        return htmlspecialchars($sanitized, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    protected static function sanitizeSheetName(string $sheetName): string
    {
        $sheetName = trim(str_replace(['*', '?', ':', '[', ']', '\\', '/'], ' ', $sheetName));

        if ($sheetName === '') {
            $sheetName = 'Sheet1';
        }

        if (mb_strlen($sheetName) > 31) {
            $sheetName = mb_substr($sheetName, 0, 31);
        }

        return $sheetName;
    }
}
