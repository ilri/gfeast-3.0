<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, device-width, initial-scale=1.0">
        <title>Bulletin</title>
        <!-- Include jQuery -->
        <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
        <!-- Include SweetAlert2 for notifications -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- Include Highcharts and modules -->
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
        <!-- Include jsPDF -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <!-- Include html2canvas -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <style>
            #pdfContent {
                margin: 20px;
            }
            #landholding {
                width: 100%;
                height: 450px;
            }
        </style>
    </head>
    <body>
        <!-- <div id="pdfContent">
            <h1>Land Holding Report</h1>
            <button id="downloadLandHolding" style="margin: 0 0 0 200px">Generate Land Holding PDF</button>
            <div id="landholding"></div>
        </div> -->

        <script>
            // Access jsPDF from the global scope
            const { jsPDF } = window.jspdf;

            // List of chart IDs
            const chartIds = [
                'households_by_landholding_category',
                'crop_types_by_average_hectares_cultivated',
                'crops_grown_in_the_area',
                'average_hectares_cultivated_per_household_by_fodder_crop_type',
                'average_hectares_cultivated_per_household_by_fodder_crop_type_all',
                'average_kg_of_feed_purchased_per_household_by_feed_type',
                'average_kg_of_feed_purchased_per_household_by_feed_type_all',
                'available_feed_resources',
                'average_price_of_major_livestock_species_in_usd_by_month',
                'average_daily_milk_yield_vs_average_price_received_per_liter',
                'contribution_of_livelihood_activities_to_household_income',
                'dominant_livestock_categories_by_average_tlus_per_household',
                'average_household_livestock_holdings_by_category_in_tropical_livestock_units',
                'average_household_livestock_holdings_by_type_in_tropical_livestock_units',
                'dry_matter_intake',
                'metabolisable_energy_intake',
                'crude_protein_intake',
                'average_daily_labour_rates_by_gender'
            ];

            // Ensure charts are visible before capturing
            function ensureChartVisible(chartId) {
                const container = document.getElementById(chartId);
                if (container) {
                    container.style.display = 'block';
                    Highcharts.charts.forEach(chart => {
                        if (chart && chart.renderTo && chart.renderTo.id === chartId) {
                            chart.reflow();
                        }
                    });
                }
            }

            // PDF Export functionality
            $('#downloadLandHolding').on('click', function() {
                console.log('Starting PDF generation...');
                const name = prompt('Enter the file name for the PDF:');
                if (!name || !name.trim()) {
                    Swal.fire('Error!', 'Please enter a valid file name', 'error');
                    return;
                }

                // Mock existing titles (replace with actual data from your backend)
                const existingTitles = []; // Fetch via AJAX if needed
                if (existingTitles.includes(name.toLowerCase())) {
                    Swal.fire('Error!', 'A report or template with this name already exists. Please choose a unique name.', 'error');
                    return;
                }

                $(this).prop('disabled', true);
                $(this).html('<span class="spinner-border spinner-border-sm" role="status"></span> Generating...');

                const pdf = new jsPDF({
                    orientation: 'landscape',
                    unit: 'mm',
                    format: 'a4'
                });

                const pageWidth = 297;
                const marginLeft = 20;
                const marginRight = 20;
                const marginTop = 30;
                const maxChartWidth = pageWidth - marginLeft - marginRight; // 257mm
                const chartHeight = 110;

                // Cover page
                pdf.setFontSize(24);
                pdf.setTextColor(70, 72, 85);
                pdf.text('Comprehensive Report', 148.5, 80, { align: 'center' });
                pdf.setFontSize(16);
                pdf.setTextColor(102, 102, 102);
                const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                const today = new Date();
                const date = `${days[today.getDay()]}, ${String(today.getDate()).padStart(2, '0')} ${months[today.getMonth()]}`;
                pdf.text(`Generated on ${date}`, 148.5, 100, { align: 'center' });
                pdf.setFontSize(14);
                pdf.setFontSize(10);
                pdf.setTextColor(100);
                pdf.text('Comprehensive Report', 20, 10);
                pdf.text('Page 1 of ' + (chartIds.length + 1), 257, 200);

                // Add logo (if available)
                const logo = '<?php echo base_url("include/dist/img/ilri_logo.png"); ?>';
                const logoImg = new Image();
                logoImg.src = logo;

                // Function to capture chart as image
                function captureChart(chartId, callback) {
                    console.log(`Capturing chart: ${chartId}`);
                    ensureChartVisible(chartId);
                    let chart = null;
                    Highcharts.charts.forEach(c => {
                        if (c && c.renderTo && c.renderTo.id === chartId) {
                            chart = c;
                        }
                    });

                    if (!chart) {
                        console.warn(`Chart with ID ${chartId} not found.`);
                        callback(null);
                        return;
                    }

                    try {
                        const svg = chart.getSVG({
                            chart: { width: 1300, height: 500 },
                            exporting: { scale: 2 }
                        });
                        console.log(`SVG length for ${chartId}: ${svg.length}`);

                        const base64Svg = btoa(unescape(encodeURIComponent(svg)));
                        const img = new Image();
                        img.src = 'data:image/svg+xml;base64,' + base64Svg;

                        img.onload = function() {
                            console.log(`SVG loaded for ${chartId}`);
                            const canvas = document.createElement('canvas');
                            canvas.width = 1300;
                            canvas.height = 500;
                            const ctx = canvas.getContext('2d');
                            ctx.fillStyle = '#fff';
                            ctx.fillRect(0, 0, canvas.width, canvas.height);
                            ctx.drawImage(img, 0, 0, 1300, 500);
                            const imageData = canvas.toDataURL('image/jpeg', 0.98);
                            console.log(`Image data generated for ${chartId}: ${imageData.substring(0, 50)}...`);
                            callback(imageData);
                        };

                        img.onerror = function() {
                            console.warn(`Failed to load SVG for ${chartId}. Falling back to html2canvas...`);
                            const chartContainer = document.getElementById(chartId);
                            if (!chartContainer) {
                                console.error(`Chart container ${chartId} not found for html2canvas.`);
                                callback(null);
                                return;
                            }
                            html2canvas(chartContainer, {
                                scale: 2,
                                width: 1300,
                                height: 500,
                                backgroundColor: '#fff',
                                logging: true
                            }).then(canvas => {
                                const imageData = canvas.toDataURL('image/jpeg', 0.98);
                                console.log(`html2canvas image data for ${chartId}: ${imageData.substring(0, 50)}...`);
                                callback(imageData);
                            }).catch(err => {
                                console.error(`html2canvas failed for ${chartId}:`, err);
                                callback(null);
                            });
                        };
                    } catch (err) {
                        console.error(`Error capturing chart ${chartId}:`, err);
                        callback(null);
                    }
                }

                // Process charts sequentially
                let chartIndex = 0;
                let pageCount = 2;
                let failedCharts = [];

                function processNextChart() {
                    if (chartIndex >= chartIds.length) {
                        console.log('All charts processed, finalizing PDF...');
                        // Attempt to add logo, but don't rely on it for PDF saving
                        try {
                            if (logoImg.complete && logoImg.naturalWidth !== 0) {
                                const imgWidth = 50;
                                const imgHeight = (logoImg.height * imgWidth) / logoImg.width;
                                pdf.addImage(logoImg, 'PNG', 20, 20, imgWidth, imgHeight);
                                console.log('Logo added to PDF.');
                            } else {
                                console.warn('Logo not loaded, skipping...');
                            }
                        } catch (err) {
                            console.error('Error adding logo:', err);
                        }
                        finalizePdf();
                        return;
                    }

                    const chartId = chartIds[chartIndex];
                    captureChart(chartId, function(imageData) {
                        if (imageData) {
                            try {
                                pdf.addPage();
                                pdf.setFontSize(20);
                                pdf.setTextColor(70, 72, 85);
                                pdf.text(chartId.replace(/_/g, ' ').toUpperCase(), marginLeft, 30);
                                pdf.setFontSize(16);
                                pdf.setTextColor(102, 102, 102);
                                pdf.text(`Details for ${chartId.replace(/_/g, ' ')}`, marginLeft, 40);
                                pdf.addImage(imageData, 'JPEG', marginLeft, marginTop + 20, maxChartWidth, chartHeight);
                                pdf.setFontSize(10);
                                pdf.setTextColor(100);
                                pdf.text('Comprehensive Report', 20, 10);
                                pdf.text(`Page ${pageCount} of ${chartIds.length + 1}`, 257, 200);
                                console.log(`Added chart ${chartId} to PDF on page ${pageCount}`);
                                pageCount++;
                            } catch (err) {
                                console.error(`Error adding chart ${chartId} to PDF:`, err);
                                failedCharts.push(chartId);
                            }
                        } else {
                            failedCharts.push(chartId);
                        }
                        chartIndex++;
                        processNextChart();
                    });
                }

                function finalizePdf() {
                    try {
                        console.log('Saving PDF...');
                        pdf.save(`${name}.pdf`);
                        console.log('PDF save initiated.');
                    } catch (err) {
                        console.error('Error saving PDF:', err);
                        Swal.fire('Error!', 'Failed to save PDF. Please try again.', 'error');
                    } finally {
                        $('#downloadLandHolding').prop('disabled', false).html("Generate Land Holding PDF");
                        if (failedCharts.length > 0) {
                            Swal.fire('Warning', `Some charts could not be included: ${failedCharts.join(', ')}`, 'warning');
                        }
                    }
                }

                // Start processing charts
                chartIds.forEach(chartId => ensureChartVisible(chartId));
                processNextChart();
            });
        </script>
    </body>
</html>