function addTableLoader(loader) {
  var element = `<div class="table-loader-open">
        
    </div>`;
  $(loader).append(element);
}

function addTableLoaderWithLoad(loader) {
  var element = `<div class="table-loader-open" style="margin:0 !important">
        <div class="lds-ring">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>`;
  $(loader).append(element);
}

function deleteTableLoader(loader) {
  $(loader).empty();
}
