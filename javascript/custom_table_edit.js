
function tableData (){
    $('#editable-table').Tabledit({
        url: '../php/live_edit.php',
        columns: {
            identifier: [0, 'Sale_ID'],
            editable: [
                [1, 'Item_ID'], 
                [2, 'Product_Name'], 
                [3, 'Price'],
                [4, 'Quantity_Sold'], 
                [5, 'Date_Sold'], 
                [6, 'Discount']
            ]
        }
    });
}


function viewData()
{
    $.ajax({
        url: '../php/live_edit.php?p=view',
        method: 'GET'
    }).done(function(data)
    {
        $('tbody').html(data)
        tableData()
    })
}