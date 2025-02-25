document.addEventListener('DOMContentLoaded',(event)=>{
    document.querySelectorAll('.done').forEach(button=>{
        button.addEventListener('click',function(){
            const taskId = this.closest('.task-container').dataset.taskId;
            fetch(`/tasks/done/${taskId}`,{
                method: 'POST',
                headers:{
                    'X-Requested-With' : 'XMLHttpRequest',
                    'Content-Type' : 'application/json'
                }
            }).then(response => response.json())
              .then(data=>{
                if(data.status === 'done'){
                    location.reload();
                }else{
                    console.error('Error: ',data);
                }
              }).catch(error=>{
                console.error('error: ',error);
              });

        });
    });

    document.querySelectorAll('.remove').forEach(button=>{
        button.addEventListener('click',function(){
            const taskId =  this.closest('.task-container').dataset.taskId;
            fetch(`/tasks/remove/${taskId}`,{
                method : 'DELETE',
                headers:{
                    'X-Requested-With' : 'XMLHttpRequest',
                    'Content-Type':'application/json'
                } 
            }).then(response=>response.json())
              .then(data=>{
                if(data.status === 'removed'){
                    location.reload();
                }else{
                    console.error('Error: ',data);
                }
              }).catch(error=>{
                console.error('Error: ',error);
              });
        });
    });
});