  function get_selected_items_num() {
      return $(".checkbox input[type='checkbox']:checked").length;
  }

  $(document).ready(
      function () {
          var pd = function (event) {
              event.preventDefault();
          };

          if (get_selected_items_num() == 0) {
              $(".disabled").click(pd);
          } else {
              $(".disabled").off("click");
              $("#btn-submit").removeClass("disabled");
          }

          $(".checkbox input[type='checkbox']").change(
              function () {
                  $this = $(this);
                  if (get_selected_items_num() == 0) {
                      $("#btn-submit").click(pd);
                      $("#btn-submit").addClass("disabled");
                  } else {
                      $("#btn-submit").off("click");
                      $("#btn-submit").removeClass("disabled");
                  }
              }
          )
      }
  );