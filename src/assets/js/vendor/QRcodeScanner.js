    HTMLCanvasElement.prototype.getContext = (function(originalFunction) {
        return function(type, attributes) {
            if (type === '2d') {
                attributes = attributes || {};
                attributes.willReadFrequently = true; // Set the attribute
            }
            return originalFunction.call(this, type, attributes);
        };
    }) (HTMLCanvasElement.prototype.getContext);

    var scanner = new Instascan.Scanner({ video: document.getElementById('preview'), scanPeriod: 5, mirror: false });
    Instascan.Camera.getCameras().then(function(cameras) {
        if (cameras.length > 0) {
            scanner.start(cameras[0]);
            $('[name="options"]').on('change', function() {
                if ($(this).val() == 1) {
                    if (cameras[0] != "") {
                        scanner.start(cameras[0]);
                    } else {
                        alert('No Front camera found!');
                    }
                } else if ($(this).val() == 2) {
                    if (cameras[1] != "") {
                        scanner.start(cameras[1]);
                    } else {
                        alert('No Back camera found!');
                    }
                }
            });
        } else {
            console.error('No cameras found.');
            alert('No cameras found.');
        }
    }).catch(function(e) {
        console.log(e);
        document.getElementById('catchError').innerText = 'No Video Stream Found!';
    });
    
    scanner.addListener('scan', function(c) {
        if (c) {
            $(function() {
                var Toast = Swal.mixin({
                    toast: true,
                    position: 'bottom-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                Toast.fire({
                    icon: 'success',
                    title: 'Reward Points Successfully Claimed!'
                });
            });
            document.getElementById('scanned_code').innerText = c;
        } else if (c === null || c === '') {
            $(function() {
                var Toast = Swal.mixin({
                    toast: true,
                    position: 'bottom-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                Toast.fire({
                    icon: 'error',
                    title: 'QR Code Invalid!'
                });
            });
            document.getElementById('scanned_code').innerText = 'QR Code Invalid!';
        } else {
            document.getElementById('scanned_code').innerText = 'QR Code Invalid!';
        }
        console.log('Scanned QR Code:', c); // Logs the scanned QR code
        document.getElementById('member_qrcode').value = c;
        //document.forms[0].submit();
    });
