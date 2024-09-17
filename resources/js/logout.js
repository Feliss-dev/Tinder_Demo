window.addEventListener('beforeunload', function(event){
    event.preventDefault();
    this.window.location.href = '/logout';
});
