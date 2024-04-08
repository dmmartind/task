(function () {

    /******************************************************************************************
     *
     *   reloadList ()
     *  Desc: gets the array of tasks from backend and utilizes a promise to wait for the data.
     */
    function reloadList() {
        let stored;

        let myPromise = new Promise(function(myResolve, myReject) {
            $.get("task/getlist").done(function(data){
                stored = data.data;
                if(stored)
                {
                    myResolve(stored); // when successful
                }
                else
                    myReject("error");  // when error
            });
        });

        myPromise.then(
            function(value) {finish(value);},
            function(error) {fin(error);}
        );
    }


    /******************************************************************************************
     *
     *   windowLoadHandler ()
     *  arg:
     *   desc: handle function to reload the task list and set event listener to the toggle all button and the
     *   new task input
     */
    window.addEventListener('load', windowLoadHandler, false);
    function windowLoadHandler() {
        reloadList();
        document.getElementById('toggle-all').addEventListener('change', toggleAllHandler, false);
        document.getElementById('new-todo').addEventListener('keypress', newTodoKeyPressHandler, false);
    }
}());
