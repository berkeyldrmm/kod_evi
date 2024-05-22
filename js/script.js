isUp=true;

var shapeOptions=[
    "polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%)",
    "polygon(50% 0%, 100% 38%, 82% 100%, 18% 100%, 0% 38%)",
    "ellipse(40% 40% at 50% 50%)",
    "polygon(0% 0%, 75% 0%, 100% 50%, 75% 100%, 0% 100%)",
    "polygon(20% 0%, 80% 0%, 100% 79%, 0% 100%)"
  ]

function createShape() {
    var shape = document.createElement('span');
    shape.classList.add('shape');
    if(isUp){
        shape.style.left = Math.random() * window.innerWidth + 'px';
        shape.style.top = '99%';
        shape.setAttribute('data-direction',1);
    }
    else{
        shape.style.left = Math.random() * window.innerWidth + 'px';
        shape.style.top = '5%';
        shape.setAttribute('data-direction',0);
    }
    shape.style.clipPath=shapeOptions[Math.floor(Math.random()*5)];
    shape.style.height = shape.style.width = Math.random()*50+80 + 'px';
    document.querySelector('.sekiller').appendChild(shape);
    if(isUp){
        isUp=false;
    }
    else{
        isUp=true;
    }
  }

  function removeShape(){
    var sekillerdiv=document.querySelector('.sekiller');
    var firstChild = sekillerdiv.firstElementChild;
    sekillerdiv.removeChild(firstChild);
  }

  createShape();
  setInterval(createShape, 5000);

  setTimeout(() => {
    setInterval(removeShape, 5000);
  }, 10000);

  $("#searchButton").click(()=>{
    $("#searchInput").css('width', '250px');
    $("#searchInput").css('padding', '0 6px');
  });

  var checkboxes=document.querySelectorAll('input[type="radio"]');

  checkboxes.forEach(checkbox => {
    checkbox.addEventListener("change", function() {
      checkboxes.forEach(checkbox=>{
        if (checkbox.checked) {
          var label=document.querySelector('label[for="'+checkbox.id+'"]');
          label.classList.add("bg-light");
        }
        else {
          var label=document.querySelector('label[for="'+checkbox.id+'"]');
          label.classList.remove("bg-light");
        }
      });
      });
  });

  $("#editButton").click((e)=>{
    console.log("mkefle");
    e.preventDefault();
    $("#home").css("display","none");
    $("#editProfile").css("display","block");
  })