$(document).ready(function() {
                  
                  $.ajax({
                         url : "http://x81000201.altervista.org/Chart/api/data.php",
                         type : "GET",
                         success : function(data){
                         console.log(data);
                         
                         var user = {variation : [], date : []};
                         
                         var len = data.length;
                         
                         for (var i = 0; i < len; i++) {
                         user.variation.push(data[i].weight);
                         user.date.push(data[i].date);
                         }
                         
                         //get canvas
                         var ctx = $("#line-chartcanvas2");
                         
                         var data = {
                         labels : user.date,
                         datasets : [
                                     {
                                     label : "Peso",
                                     data : user.variation,
                                     backgroundColor : "red",
                                     borderColor : "red",
                                     fill : false,
                                     lineTension : 0,
                                     pointRadius : 5
                                     }
                                     ]
                         };
                         
                         var options = {
                         title : {
                         display : true,
                         position : "top",
                         text : "Peso",
                         fontSize : 18,
                         fontColor : "#111"
                         },
                         legend : {
                         display : true,
                         position : "bottom"
                         }
                         };
                         
                         var chart = new Chart( ctx, {
                                               type : "line",
                                               data : data,
                                               options : options
                                               } );
                         
                         },
                         error : function(data) {
                         console.log(data);
                         }
                         });
                  
                  });
