<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 footer-copyright">
                <p class="mb-0">Copyright 2019 © La Mode Parisienne All rights reserved.</p>
            </div>
            <div class="col-md-6">
                <p class="pull-right mb-0">Hand crafted & made with<i class="fa fa-heart"></i></p>
            </div>
        </div>
    </div>
</footer>
<!-- footer end-->

</div>

</div>

<!-- latest jquery-->
<script src="../assets/js/jquery-3.3.1.min.js"></script>

<!-- Bootstrap js-->
<script src="../assets/js/popper.min.js"></script>
<script src="../assets/js/bootstrap.js"></script>

<!-- feather icon js-->
<script src="../assets/js/icons/feather-icon/feather.min.js"></script>
<script src="../assets/js/icons/feather-icon/feather-icon.js"></script>

<!-- Sidebar jquery-->
<script src="../assets/js/sidebar-menu.js"></script>

<!-- Jsgrid js-->
<script src="../assets/js/jsgrid/jsgrid.min.js"></script>
<script src="../assets/js/jsgrid/griddata-digital.js"></script>
<script src="../assets/js/jsgrid/jsgrid-manage-product.js"></script>

<!-- lazyload js-->
<script src="../assets/js/lazysizes.min.js"></script>



<!--right sidebar js-->
<script src="../assets/js/chat-menu.js"></script>

<!--script admin-->
<script src="../assets/js/admin-script.js"></script>
<script>
    function delete_oldImage() {
        var oldImage = document.getElementById('oldImage');
        oldImage.remove();
    }

    function previewImages() {
        var $preview = $('#preview-image').empty();
        if (this.files) $.each(this.files, readAndPreview);

        function readAndPreview(i, file) {
            if (!/\.(jpe?g|png|gif)$/i.test(file.name)) {
                return alert(file.name + " is not an image");
                file.delete();
            } // else...    
            var reader = new FileReader();
            $(reader).on("load", function() {
                $preview.append($("<img/>", {
                    src: this.result,
                    height: 100
                }));
            });
            reader.readAsDataURL(file);
        }
    }
    $('#input-image').on("change", previewImages);
</script>
</body>

</html>