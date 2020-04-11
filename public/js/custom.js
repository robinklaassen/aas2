function makeTableSortable(selector) {
  var jTable = $(selector);

  function getColumnOptions() {
    let columnOptions = [];
    jTable.find("thead th").each(function (i) {
      let option =
        $(this).data("orderable") !== undefined ? null : { orderable: false };
      columnOptions.push(option);
    });
    return columnOptions;
  }

  jTable.DataTable({
    paging: false,
    order: [[0, "asc"]],
    columns: getColumnOptions(),
  });
}

function copyToClipboard(clickElement, idCopyElement) {
  var copyEl = document.querySelector(idCopyElement);
  var range = document.createRange();
  range.selectNodeContents(copyEl);
  var sel = window.getSelection();
  sel.removeAllRanges();
  sel.addRange(range);

  document.execCommand("copy");

  $(clickElement).tooltip({
    title: "Gekopieerd",
    trigger: "manual",
  });
  $(clickElement).tooltip("show");

  setTimeout(function () {
    $(clickElement).tooltip("hide");
  }, 3000);
}
