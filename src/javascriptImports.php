<script src="lib/js/jquery.min.js"></script>
    <script src="js/script.js"></script>
    <script src="lib/codemirror-5.65.14/lib/codemirror.js"></script>
    <script src="lib/codemirror-5.65.14/mode/javascript/javascript.js"></script>
    <script src="lib/js/popper.min.js" ></script>
    <script src="lib/js/bootstrap.min.js"></script>
    <script src="lib/js/jquery-slim.js"></script>
      
    <script>
      document.querySelectorAll(".editor").forEach(element => {
          var editor = CodeMirror.fromTextArea(element, {
          lineNumbers: true,
          matchBrackets: true,
          continueComments: "Enter",
          extraKeys: {"Ctrl-Q": "toggleComment"}
        });
      });
      </script>