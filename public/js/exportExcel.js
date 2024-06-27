var tablesToExcel = (function () {
    var uri = "data:application/vnd.ms-excel;base64,",
        tmplWorkbookXML =
            '<?xml version="1.0"?><?mso-application progid="Excel.Sheet"?><Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">' +
            '<DocumentProperties xmlns="urn:schemas-microsoft-com:office:office"><Author>Axel Richter</Author><Created>{created}</Created></DocumentProperties>' +
            "<Styles>" +
            '<Style ss:ID="Currency"><NumberFormat ss:Format="Currency"></NumberFormat></Style>' +
            '<Style ss:ID="Date"><NumberFormat ss:Format="Medium Date"></NumberFormat></Style>' +
            '<Style ss:ID="Header"><Font ss:Size="25" ss:Bold="1" /><Alignment ss:Horizontal="Center" /></Style>' +
            '<Style ss:ID="Bold"><Font ss:Size="13" ss:Bold="1" /></Style>' + // Add Bold style
            '<Style ss:ID="Row"><Font ss:Size="12" /><Alignment ss:Horizontal="Left" ss:Vertical="Center" /></Style>' +
            "</Styles>" +
            "{worksheets}</Workbook>",
        tmplWorksheetXML =
            '<Worksheet ss:Name="{nameWS}"><Table>{rows}</Table></Worksheet>',
        tmplCellXML =
            '<Cell{attributeStyleID}{attributeFormula}><Data ss:Type="{nameType}">{data}</Data></Cell>',
        base64 = function (s) {
            return window.btoa(unescape(encodeURIComponent(s)));
        },
        format = function (s, c) {
            return s.replace(/{(\w+)}/g, function (m, p) {
                return c[p];
            });
        };
    return function (tables, wsnames, wbname, appname, headerText) {
        var ctx = "";
        var workbookXML = "";
        var worksheetsXML = "";
        var rowsXML = "";

        for (var i = 0; i < tables.length; i++) {
            if (!tables[i].nodeType)
                tables[i] = document.getElementById(tables[i]);

            // Add header row with custom style
            rowsXML += "<Row>";
            rowsXML +=
                '<Cell ss:StyleID="Header" ss:MergeAcross="' +
                (tables[i].rows[0].cells.length - 1) +
                '"><Data ss:Type="String">' +
                headerText +
                "</Data></Cell>";
            rowsXML += "</Row>";

            for (var j = 0; j < tables[i].rows.length; j++) {
                rowsXML += "<Row>"; // Remove ss:Width attribute from Row

                var columnsXML = "";
                for (var k = 0; k < tables[i].rows[j].cells.length; k++) {
                    var cellWidth = 100; // Adjust width as needed
                    columnsXML +=
                        '<Column ss:AutoFitWidth="0" ss:Width="' +
                        cellWidth +
                        '"/>';
                    var dataType =
                        tables[i].rows[j].cells[k].getAttribute("data-type");
                    var dataStyle =
                        tables[i].rows[j].cells[k].getAttribute("data-style");
                    var dataValue =
                        tables[i].rows[j].cells[k].getAttribute("data-value");
                    dataValue = dataValue
                        ? dataValue
                        : tables[i].rows[j].cells[k].innerHTML;
                    var dataFormula =
                        tables[i].rows[j].cells[k].getAttribute("data-formula");
                    dataFormula = dataFormula
                        ? dataFormula
                        : appname == "Calc" && dataType == "DateTime"
                        ? dataValue
                        : null;

                    // Set width for the cell
                    var cellWidth = 100; // Adjust this value as needed
                    ctx = {
                        attributeStyleID:
                            j === 0
                                ? ' ss:StyleID="Bold"' // Apply Bold style to the first row
                                : dataStyle == "Currency" || dataStyle == "Date"
                                ? ' ss:StyleID="' + dataStyle + '"'
                                : "",
                        nameType:
                            dataType == "Number" ||
                            dataType == "DateTime" ||
                            dataType == "Boolean" ||
                            dataType == "Error"
                                ? dataType
                                : "String",
                        data: dataFormula ? "" : dataValue,
                        attributeFormula: dataFormula
                            ? ' ss:Formula="' + dataFormula + '"'
                            : "",
                        cellWidth: ' ss:Width="' + cellWidth + '"', // Add width attribute for the cell
                    };
                    rowsXML += format(tmplCellXML, ctx);
                }
                rowsXML += "</Row>";
            }

            ctx = { rows: rowsXML, nameWS: wsnames[i] || "Sheet" + i };
            worksheetsXML += format(tmplWorksheetXML, ctx);
            rowsXML = "";
        }

        ctx = { created: new Date().getTime(), worksheets: worksheetsXML };
        workbookXML = format(tmplWorkbookXML, ctx);

        console.log(workbookXML);

        var link = document.createElement("A");
        link.href = uri + base64(workbookXML);
        link.download = wbname || "Workbook.xls";
        link.target = "_blank";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    };
})();
