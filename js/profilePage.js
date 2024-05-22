$("#editButton").click((e)=>{
    e.preventDefault();
    $("#home").css("display","none");
    $("#editProfile").css("display","block");
    $("#editButton").css("display","none");
  });

  $("#searchButton").click(()=>{
    $("#searchInput").css('width', '250px');
    $("#searchInput").css('padding', '0 6px');
  });

  $("#home-tab").click((e)=>{
    e.preventDefault();
    $("#editProfile").css("display","none");
    $("#home").css("display","block");
    $("#editButton").css("display","block");
  });

  $("#profile-tab").click((e)=>{
    e.preventDefault();
    $("#home").css("display","none");
    $("#editProfile").css("display","none");
  });