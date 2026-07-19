<!DOCTYPE html>
<html>
  <head> 
  
    @include('admin.css')

    <style>
        label{
            display: inline-block;
            width: 200px;
            color: white;
        }
        .div_deg{
            padding: 10px;
        }
    </style>

  </head>
  <body>
    
    @include('admin.header')

    @include('admin.sidebar')
    

      <div class="page-content">
        <div class="page-header">
          <div class="container-fluid">
            
            
          <form action="{{url('upload_food')}}" method="POST" enctype="multipart/form-data">
            @csrf


            @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                 @endforeach
            </ul>
          </div>
            @endif


                <div class="div_deg">
                    <label for="">Food title</label>
                    <input type="text" name="title" required>

                </div>

                <div class="div_deg">
                    <label for="">Food details</label>
                    <textarea name="details" cols="50" rows="5" required></textarea>

                </div>

                <div class="div_deg">
                    <label for="">Price</label>
                    <input type="text" name="price" required>

                </div>

                <div class="div_deg">
    <label for="">Category</label>
    <select name="category_id" required>
        <option value="">Select Category</option>
        @foreach($topCategories as $top)
            <option value="{{ $top->id }}">{{ $top->name }}</option>
        @endforeach
    </select>
    <small class="text-light d-block mt-2">Choose one of the main categories: Value Meal, Drinks, or Desserts.</small>
</div>

                <div class="div_deg">
                    <label for="">Image</label>
                    <input type="file" name="img" required>

                </div>

                <div class="div_deg">
                    
                    <input type="submit" value="Add Food" class="btn btn-warning">

                </div>



          </form>

          </div>
      </div>
    </div>
    <!-- JavaScript files-->
    @include('admin.js')
  </body>
</html>