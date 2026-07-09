<!DOCTYPE html>
<html>
  <head> 
  
    @include('admin.css')

    <style>
        table{
           border: 1px solid skyblue; 
           margin: auto;
           width: 1000px;
        }
        th{
            color: white;
            background-color: red;
            color: white;
            font-weight: bold;
            font-size: 18px;
            text-align: center;
            padding: 10px;
        }
        td{
            color: white;
            padding: 10px;
            font-weight: bold;
            text-align: center;
        }
</style>
  </head>
  <body>
    
    @include('admin.header')

    @include('admin.sidebar')
    

      <div class="page-content">
        <div class="page-header">
          <div class="container-fluid">
            
            <table>
                <tr >
                    <th >Email</th>
                    <th>Phone</th>
                    <th >Address</th>
                    <th >Customer Name</th>
                    <th >Food Name</th>
                    <th >Price</th>
                    <th >Quantity</th>
                    <th >Image</th>
                    <th >Delivery Status</th>
                    <th >Change Status</th>
                </tr>

                @foreach($data as $data)
                <tr>
                    <td>{{$data->name}}</td>
                    <td>{{$data->email}}</td>
                    <td>{{$data->phone}}</td>
                    <td>{{$data->address}}</td>
                    <td>{{$data->title}}</td>
                    <td>{{$data->price}}</td>
                    <td>{{$data->quantity}}</td>
                    <td><img src="/food_img/{{$data->image}}" alt="" width="100" height="100"></td>
                    <td>{{$data->delivery_status}}</td>

                    <td>
                        <a onclick="return confirm('Are you sure you want to mark this order as on the way?');" class="btn btn-info" href="{{url('on_the_way', $data->id)}}">On the way</a>
                        <a onclick="return confirm('Are you sure you want to mark this order as delivered?');" class="btn btn-warning" href="{{url('delivered', $data->id)}}">Delivered</a>
                        <a onclick="return confirm('Are you sure you want to cancel this order?');" class="btn btn-danger" href="{{url('canceled', $data->id)}}">Cancel</a>
                    </td>

                </tr>

                @endforeach
            </table>    

          </div>
      </div>
    </div>
    <!-- JavaScript files-->
    @include('admin.js')
  </body>
</html>