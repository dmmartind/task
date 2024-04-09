(function () {


    function reloadList(item) {
        console.log("reload");
        var stored = localStorage.getItem('todo-list');
        if (stored) {
            todoListItems = JSON.parse(stored);
            console.log(todoListItems);
            migrateData();
        }
        redrawList();
    }

    window.addEventListener('load', windowLoadHandler, false);
    function windowLoadHandler() {
        console.log("load");
        reloadList();
        document.getElementById('toggle-all').addEventListener('change', toggleAllHandler, false);
        //console.log(document.getElementById('undo-all'));
        //document.getElementById('undo-all').addEventListener('change', undocheckboxHandler, false);
        document.getElementById('new-todo').addEventListener('keypress', newTodoKeyPressHandler, false);
    }
}());
