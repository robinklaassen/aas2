function makeTableSortable(selector) {
  var jTable = $(selector);

  function getColumnOptions() {
    let columnOptions = [];
    jTable.find("thead th").each(function(i) {
      let option =
        $(this).data("orderable") !== undefined ? null : { orderable: false };
      columnOptions.push(option);
    });
    return columnOptions;
  }

  jTable.DataTable({
    paging: false,
    order: [[0, "asc"]],
    columns: getColumnOptions()
  });
}
