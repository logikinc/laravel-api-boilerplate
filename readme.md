## Api Boilerplate

This is a boilerplate for an API

Please feel free to contribute / improve the boilerplate

##### Code Standards
---

We are utilising the repository pattern for the API code base
[Repository Pattern very basicIntroduction](https://bosnadev.com/2015/03/07/using-repository-pattern-in-laravel-5/) Along with 
[Json API Specification](http://jsonapi.org/)


##### Packages and providers

---

###### Production Dependancies
[JWT Auth](https://github.com/tymondesigns/jwt-auth) | 
[Dingo Api](https://github.com/dingo/api) | 
[Laravel Cors](https://github.com/barryvdh/laravel-cors) | 
[Repository Package](https://github.com/andersao/l5-repository) | 
[Permissions and Roles](https://github.com/spatie/laravel-permission) | 

###### Development Dependancies
[Flexable ENV File](https://github.com/svenluijten/flex-env)

##### Development documentation
--- 

## Installation



### Get the code

clone the repo and install the dependancies

```terminal
git clone git@bitbucket.org:onnet/laravel.api.boilerplate.git
cd laravel.api.boilerplate
```

Install via Bash Installer
```
sh local-install.sh
```


Or Manual Installation

```
cp .env.example .env && php artisan key:generate
```

Remove the git references so that you have a clean code base

```terminal
rm -rf .git
```

Initialize the repo to point to your new repository
We assume that this is set up 

```terminal
git init
git remote add {url-to-repo}
git add . 
git commit -am 'I am making an API'
git push -u origin master
```


### Setup a database if not using a docker container

```terminal
echo create database {database_name} | mysql -u root
php artisan migrate
```

### Create a new api user to get access

```terminal
php artisan tinker
App\Models\User::create(['email' => '{email}', 'name' => '{name}', 'password' => '{password}' ]);
```

### generate a jwt secret and add it to your .env file

```terminal
php artisan jwt:generate
```

This will output a secret key, copy it and run the following command to set it

```
php artisan env:set JWT_SECRET {copied_text}
```

#### Ready to go! 


##### Installation Via Docker

Via Bash Installer
```
sh install.sh

```

Manually from the root of the cloned repo

```
cp .env.example .env

git clone https://github.com/Laradock/laradock.git
cd laradock
cp env-example .env
sed -i -e 's/PHP_VERSION=71/PHP_VERSION=70/g' .env
cd mysql
sed -i -e 's/MYSQL_VERSION=8.0/MYSQL_VERSION=5.7/g' Dockerfile
cd ../
docker-compose up -d nginx mysql redis php-fpm php-worker
docker-compose exec workspace bash
cd /var/www

```



##### Mac OS via Valet

Install Composer

```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '669656bab3166a7aff8a7506b8cb2d1c292f042046c5a994c43155c0be6190fa0355160742ab2e1c88d40d5be660b410') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
mv composer.phar /usr/local/bin/composer
```

Install PHP

```
brew install php71
```

Install Laravel Valet

```
composer global require laravel/valet
cd {directory/of/your/projects}
valet install
valet park
```

Install MySQL

```
brew install mysql
brew services start mysql
```

#### Structure

This API uses generators to create the structure of our MVC using the repository pattern, the generator will add the respective files into the application structure is as follows

```
'generator'  => [
        'basePath'      => app_path(),
        'rootNamespace' => 'App\\',
        'paths'         => [
            'models'       => 'Models',
            'repositories' => 'Repositories',
            'interfaces'   => 'Interfaces',
            'transformers' => 'Transformers',
            'presenters'   => 'Presenters',
            'validators'   => 'Validators',
            'controllers'  => 'Http/Api/V1/Controllers',
            'provider'     => 'RepositoryServiceProvider',
            'criteria'     => 'Criteria',
        ],
        'stubsOverridePath' => app_path(),
    ]
```

# Directory structure:
```
├── app/
│   ├── Console
│   ├── Criteria
│   ├── Exceptions
│   ├── Http
│   |   ├── Api
│   │   │   ├── Auth
│   │   │   ├── V1
│   │   │   │   ├── ExampleController.php
│   │   │   ├── ApiController.php
│   |   ├── Middleware
│   |   ├── Requests
│   ├── Interfaces
│   ├── Models
│   |   ├── ExampleModel.php
│   ├── Presenters
│   |   ├── ExamplePresenter.php
│   ├── Providers
│   ├── Repositories
│   |   ├── ExampleRepository.php
│   ├── Stubs
│   ├── Transformers
│   |   ├── ExampleTransformer.php
│   ├── Validators
│   |   ├── ExampleValidator.php
├── config
├── database
├── routes
├── tests
```
The directory structure follows the repository pattern as well as the JSON API specification. This way we can reuse a lot of code to get similar results

```
$countries = $this->repository->all()
$cities = $this->repository->all()
```

In the above example both methods return all of the results for the model that it is calling, but no additional logic was written for each model. 

All Repositories come out of the box with the following methods
```
all($columns = array('*'))
first($columns = array('*'))
paginate($limit = null, $columns = ['*'])
find($id, $columns = ['*'])
findByField($field, $value, $columns = ['*'])
findWhere(array $where, $columns = ['*'])
findWhereIn($field, array $where, $columns = [*])
findWhereNotIn($field, array $where, $columns = [*])
create(array $attributes)
update(array $attributes, $id)
updateOrCreate(array $attributes, array $values = [])
delete($id)
orderBy($column, $direction = 'asc');
with(array $relations);
has(string $relation);
whereHas(string $relation, closure $closure);
hidden(array $fields);
visible(array $fields);
scopeQuery(Closure $scope);
getFieldsSearchable();
setPresenter($presenter);
skipPresenter($status = true);
```
# Usage
To create an entity, which includes the following files
```
Model
Controller
Create Request
Update Request
Interface
Presenter
Repository
Transformer
Validator
```

We can simply use a artisan command that will generate all of the above with some boilerplate code to get going on the new feature

```
php artisan make:entity {Entity}
```

## Endpoints and endpoint actions

### Creating endpoints

Endpoints are created using the `Dingo\Api\Routing\Router` Class
To create an endpoint we need to call this class and use the `$api` variable to create endpoints
```php
use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);

```
An example get endpoint will look like this
```php
$api->get('test', 'TestController@index'); // Testing get route
```
An example post endpoint will look like this
```php
$api->post('test', 'TestController@store'); // Testing store route
```

### Middleware
All routes other than the authentication routes will require a JWT token in order to hit the endpoints, this is instantiated with `jwt.auth` middleware. To use this middleware we wrap the endpoints in a route group within the middleware.

```php
$api->group(['middleware' => 'jwt.auth'], function(Router $api) {
    $api->get('test', 'TestController@index'); // Testing get route
});
```

Now if the jwt token is not included in the request, we will get a 403 forbidden exception and not be able to access the route

### API prefixing

We can add route prefixes to determine which version of the API we are trying to access using the route, for example, the below route group will only work if the request is prefixed with `v1`

EG: `http://{domain}/api/v1/test`

```php
$api->group(['middleware' => 'jwt.auth', 'prefix' => 'v1'], function(Router $api) {
    $api->get('test', 'TestController@index'); // Testing get route
});
```

### Route namespacing

Because we are using multiple versions for the API, it would be easy to clutter our routes file with duplicate code such as route namespaces.
Instead, we can wrap all of the routes within a certain namespace inside the route gropus

```php
$api->group(['middleware' => 'jwt.auth', 'prefix' => 'v1', 'namespace' => 'App\\Http\\Api\\v1\\Controllers'], function(Router $api) {
    $api->get('test', 'TestController@index'); // Testing get route
});
```
Now we do not need to redeclare `App\\Http\\Api\\v1\\Controllers\\{controller}` on each route, and can simply use `{controller}@{method}`

### Naming conventions
In REST, a resource name can be anything which is unmaintainable and hard to read, I choose to use the pluralization naming convention to keep my resources consistent. 

###### What does this mean? 
Essentially, there are two ways to name routes that are both acceptable,

E.G
`$api->get('user/{id}')` or `$api->get('users/{id}')`

I choose the latter as it keeps all of the `users` routes within a consistent naming convention and can be grouped easily if needed. 

# Endpoint actions

### Relationship Includes
When making get requests, there are times when we want to retrieve the specified resource as well as the relationshsips that come with that resource, instead of doing two calls to the server to retrieve the resource and then the relationships, we can use the `?include` URL Param to automatically include the relationships in our response

E.G
```
GET http://{domain}/api/v1/profiles/100?include=operator
```

The above example is making a get request to the `profiles` resource with an `{id}` of 100.

```
GET http://{domain}/api/v1/profiles/100
```
Which will return the values of the profile with the `{id}` of 100

```
{
  "data": {
    "id": 100,
    "type": "Profile",
    "attributes": {
      "first_name": "Isaac",
      "last_name": "Nkhuwa ",
      "birth_date": "1999-07-24",
      "msisdn": "0972936297",
      "country": "Zambia",
      "country_id": 8,
      "city": "Lusaka",
      "city_id": 459,
      "email": "nkhuwa.isaac@gmail.com",
      "lat": null,
      "lon": null
    }
  }
}
```

This resource has a one to many relationship on the Operator resource, meaning that a single operator may have many profiles, and a single profile belongs to a single operator. 

So when we use the `?include` URL Parameter, we can call the resource as well as the operator it belongs to in one query, without having to write a seperate endpoint

Request:
```
GET http://{domain}/api/v1/profiles/100?include=operator
```
Response:
```
{
  "data": {
    "id": 100,
    "type": "Profile",
    "attributes": {
      "first_name": "Isaac",
      "last_name": "Nkhuwa ",
      "birth_date": "1999-07-24",
      "msisdn": "0972936297",
      "country": "Zambia",
      "country_id": 8,
      "city": "Lusaka",
      "city_id": 459,
      "email": "nkhuwa.isaac@gmail.com",
      "lat": null,
      "lon": null
    },
    "operator": {
      "data": {
        "id": 6,
        "type": "Operator",
        "attributes": {
          "name": "Airtel",
          "country_id": 8
        }
      }
    }
  }
}
```

### Searching Resources

We are also able to search through resources using the `?search` URL Parameter. 
```
GET http://wizard.dev/api/v1/profiles/100?search=Isaac
```

Will search through the results of that particular resource and return the data that correlates to the search param. 

```
{
  "data": {
    "id": 100,
    "type": "Profile",
    "attributes": {
      "first_name": "Isaac",
      "last_name": "Nkhuwa ",
      "birth_date": "1999-07-24",
      "msisdn": "0972936297",
      "country": "Zambia",
      "country_id": 8,
      "city": "Lusaka",
      "city_id": 459,
      "email": "nkhuwa.isaac@gmail.com",
      "lat": null,
      "lon": null
    }
  }
}
```

##### Advanced Searching

We are also able to specify advanced search queries directly in the URL using the `?search` as well as `?searchFields` parameter. 

Example usage: 
Let's assume that we want to search for all of the profiles that have a country 'South Africa', we may simply add `?search=South%20Africa` as well as `&searchFields=country`

```
GET http://wizard.dev/api/v1/profiles?search=South%20Africa&searchFields=country
```

This will only return the results that have a country set to 'South Africa'. 

We can also specify that it does not need to be an exact match by adding `:like` to the end of the `searchField`
```
GET http://wizard.dev/api/v1/profiles?search=South%20Africa&searchFields=country:like
```

This will return the results where the country for the profile is similar to 'South Africa'.

Further than that, we are able to chain the searchFields to become whatever we need. 

```
GET http://wizard.dev/api/v1/profiles?search=name:John;email:john@gmail.com
```
This will search for a user where the name is 'John' and the email is 'john@gmail.com'

```
GET http://wizard.dev/api/v1/profiles?search=name:John;email:john@gmail.com&searchFields=name:like;email:=
```
And the above will search for the resource that has a name similar to 'John' and an email similar to 'john@gmail.com'

### Ordering Results

Ordering and sorting results can also be handled using `orderBy` and `sortBy` URL parameters
```
GET http://{domain}/api/v1/profiles/100?orderBy=name&sortedBy=desc
```

This will order the results by their name in descending order. 

We are also able to order the results based on their relationships
```
GET http://wizard.dev/api/v1/profiles?orderBy=operators|id&sortedBy=desc
```
Will return the results ordered by their operator id in the descending order. 

### Chaining params

We are able to chain all of the above URL parameters to output exactly what we need, an example of this with all of the above looks like this

```
GET http://wizard.dev/api/v1/profiles?search=South%20Africa&searchFields=country&orderBy=operators|id&sortedBy=asc&include=operator
```
---

## Creating Repository Entities

###### What is the Repository Pattern?

Let's assume that the API is going to be making use of ELoquent to query the database. 
In order to get all the results of a resource we would do something like this:

In a houses controller, we call the `index()` method to return all houses
```php
public function index()
	{
		$houses = House::all();
		return $houses;
	}
```
Which is a perfectly clean and acceptable way to do this, however, we now have another where we want to get people 
We would need to have another controller and call the exact same method

```php
public function index()
	{
		$people = People::all();
		return $people;
	}
```

As we can see, two controllers are now using the exact same method to get all the results from a resource, and this is where we can make this better and faster. 

Using repositories, we are able to pull out the methods that we regularly write into one file and calle them across many controllers. 

E.G

```
$this->repository->all()
```

##### Creating Entities
To create a repository entity, we need a few things, luckily, we are able to run an artisan command to create all of these with ease. 

`php artisan make:entity Foo`

This will quickly scaffold the entity for the application allowing us to code quicker and not worry about file structure and tedious organisation tasks. 

###### What does it create?

Out of the box, the artisan command will create the following files

1. Model 
2. Interface
3. CreateRequest
4. UpdateRequest
5. Controller
6. Presenter
7. Transformer
8. Validator
9. Repository
10. Migration (Delete if not needed)

Each file is generated with boilerplate to get started. 

##### Configuring the Repository

Out of the box, our newly created repository will look like this

```php
<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Interfaces\TestRepository;
use App\Models\Foo;
use App\Validators\FooValidator;

/**
 * Class TestRepositoryEloquent
 * @package namespace App\Repositories;
 */
class FooRepositoryEloquent extends BaseRepository implements TestRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Foo::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}

```

Whilst this is enough to get started, we may need to add some further configuration to the repository class. 

##### Searchable Fields

When we are using the repository, we are able to search through fields associated with that model, the fields that we are able to search through need to be configured, or the default of all fields will take effect, often we do not want to search for certain fields like `id` or `deleted_at` so we can configure which fields we should be able to search. 

```php
protected $fieldSearchable = [
        'name' => 'like',
        'type' => 'like',
        'operator',
    ];
```

in the above array, if we specify the value as `like`, we are explicitly telling the repository to look for a similar value to the query instead of an exact match. 

##### Configuring the transformer

By default, the repository will utilize a presenter, this presenter will implement a transformer class, the transformer class looks like this out of the box. 

```php
<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Foo;

/**
 * Class FooTransformer
 * @package namespace App\Transformers;
 */
class FooTransformer extends TransformerAbstract
{
    /**
     * Transform the \Profile entity
     * @param Profile $model
     *
     * @return array
     */
    public function transform(Foo $model)
    {
        $data = array_only($model->toArray(), $model->getFillable());
        return [
            'id'         => (int) $model->id,
            'type' => $model->getModelName(),
            'attributes' => $data
        ];
    }
}
```

We will notice that the following line of code will automatically map our fillable fields to our transformer in the `attributes` array

```php
$data = array_only($model->toArray(), $model->getFillable());
```

This means that our model class will need to have a `$fillable` array in order for us to see any attributes returned from the repository. Ensure that this array is not empty and only has data that you want to include in a response. 

if our model class has the following in the fillable array

```php
protected $fillable = [
    'name',
    'type'
    'operator_id'
];
```

Our response from the server will look like this

```
{
  "data": [
    {
      "id": 100,
      "type": "Profile",
      "attributes": {
        "name": "Something",
        "type": "some type ",
        "operator_id": "1"
      }
    }
  ]
}
```

##### Configuring Relationships 

In order for us to be able to use relationships within our query scopes, we need to define the relationships on our transformer class. Lets suppose that our `Foo.php` model has many devices associated with it, and belongs to one Operators. 

We can associate this by adding these functions to our `Foo.php` model

```php
public function devices()
    {
        return $this->hasMany('App\Models\Device');
    }

    public function operator()
    {
        return $this->belongsTo('App\Models\Operator');
    }
```

Now since our relationships are created within our model class, we can alter our transformer to include these relationships

```php
<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Foo;

/**
 * Class FooTransformer
 * @package namespace App\Transformers;
 */
class FooTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['operator', 'devices'];
    
    /**
     * Transform the \Profile entity
     * @param Profile $model
     *
     * @return array
     */
    public function transform(Foo $model)
    {
        $data = array_only($model->toArray(), $model->getFillable());
        return [
            'id'         => (int) $model->id,
            'type' => $model->getModelName(),
            'attributes' => $data
        ];
    }
    
    public function includeOperator(Foo $model)
    {
        if ($model->operator) {
            return $this->item($model->operator, new OperatorTransformer());
        } else {
            return null;
        }
    }
    
    public function includeDevices(Foo $model)
    {
        return $this->collection($model->devices, new DeviceTransformer());
    }
}
```

These functions will allow the transformer to include these relationships when the `?include` URL Parameter is present. 

So if we were to make a get request to 

```
Get http://{domain}/api/v1/foos?include=operator
```

our response will look like this

```
{
  "data": [
    {
      "id": 100,
      "type": "Profile",
      "attributes": {
        "name": "Something",
        "type": "some type ",
        "operator_id": 1,
      },
      "operator": {
        "data": {
          "id": 1,
          "type": "Operator",
          "attributes": {
            "name": "Airtel",
            "country_id": 8
          }
        }
      }
    }
  ]
}
```

##### Using repositories in a Controller

Out of the box, creating an entity will generate a controller that looks like this

```php
<?php

namespace App\Http\Api\V1\Controllers;

use App\Http\Api\ApiController;

use App\Models\Foo;
use App\Presenters\FooPresenter;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\FooCreateRequest;
use App\Http\Requests\FooUpdateRequest;
use App\Interfaces\FooRepository;
use App\Validators\FooValidator;


class FoosController extends ApiController
{

    /**
     * @var FooRepository
     */
    protected $repository;

    /**
     * @var FooValidator
     */
    protected $validator;

    public function __construct(FooRepository $repository, FooValidator $validator, FooPresenter $presenter)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->repository->setPresenter($presenter);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $foos = $this->repository->all();
            return $this->respondWithSuccess($foos);

        } catch(\Exception $error) {
            return $this->respondWithError($error->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $this->repository->find($id);
            return $this->respondWithSuccess($foo);

        } catch(\Exception $error) {
            return $this->respondWithError($error->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  FooCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(FooCreateRequest $request)
    {
        try {
            if(!$this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE)) {
                return $this->respondUnprocessableEntity($this->errorBag());
            }
            $foo = $this->repository->create($request->all());
            return $this->respondCreated($foo);

        } catch(\Exception $error) {
            return $this->respondWithError($error->getMessage());
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  FooUpdateRequest $request
     * @param  string            $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(FooUpdateRequest $request, $id)
    {
        try {
            if(!$this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE)) {
                return $this->respondUnprocessableEntity($this->errorBag());
            }
            $foo = $this->repository->update($request->all(), $id);
            return $this->respondCreated($foo);

        } catch(\Exception $error) {
            return $this->respondWithError($error->getMessage());
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $this->repository->delete($id);
        return $this->respondNoContent();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function restore($id)
    {
        $foo = Foo::withTrashed()->find($id);
        $foo->restore();
        return $this->respondWithSuccess($foo);
    }
}
```

The above controller comes with a full CRUD system. 

Available methods:

1. index -- Get all results
2. show -- Get result by ID
3. store -- Create a resource
4. update -- Update a resource
5. destroy -- Soft delete a resource
6. restore -- Restore Soft deleted resource

This is all just boilerplate and can be customized as needed, the repository will always have the following methods available
```
all($columns = array('*'))
first($columns = array('*'))
paginate($limit = null, $columns = ['*'])
find($id, $columns = ['*'])
findByField($field, $value, $columns = ['*'])
findWhere(array $where, $columns = ['*'])
findWhereIn($field, array $where, $columns = [*])
findWhereNotIn($field, array $where, $columns = [*])
create(array $attributes)
update(array $attributes, $id)
updateOrCreate(array $attributes, array $values = [])
delete($id)
orderBy($column, $direction = 'asc');
with(array $relations);
has(string $relation);
whereHas(string $relation, closure $closure);
hidden(array $fields);
visible(array $fields);
scopeQuery(Closure $scope);
getFieldsSearchable();
setPresenter($presenter);
skipPresenter($status = true);
```

### Use methods

```php
namespace App\Http\Controllers;

use App\PostRepository;

class PostsController extends BaseController {

    /**
     * @var PostRepository
     */
    protected $repository;

    public function __construct(PostRepository $repository){
        $this->repository = $repository;
    }

    ....
}
```

Find all results in Repository

```php
$posts = $this->repository->all();
```

Find all results in Repository with pagination

```php
$posts = $this->repository->paginate($limit = null, $columns = ['*']);
```

Find by result by id

```php
$post = $this->repository->find($id);
```

Hiding attributes of the model

```php
$post = $this->repository->hidden(['country_id'])->find($id);
```

Showing only specific attributes of the model

```php
$post = $this->repository->visible(['id', 'state_id'])->find($id);
```

Loading the Model relationships

```php
$post = $this->repository->with(['state'])->find($id);
```

Find by result by field name

```php
$posts = $this->repository->findByField('country_id','15');
```

Find by result by multiple fields

```php
$posts = $this->repository->findWhere([
    //Default Condition =
    'state_id'=>'10',
    'country_id'=>'15',
    //Custom Condition
    ['columnName','>','10']
]);
```

Find by result by multiple values in one field

```php
$posts = $this->repository->findWhereIn('id', [1,2,3,4,5]);
```

Find by result by excluding multiple values in one field

```php
$posts = $this->repository->findWhereNotIn('id', [6,7,8,9,10]);
```

Find all using custom scope

```php
$posts = $this->repository->scopeQuery(function($query){
    return $query->orderBy('sort_order','asc');
})->all();
```

Create new entry in Repository

```php
$post = $this->repository->create( Input::all() );
```

Update entry in Repository

```php
$post = $this->repository->update( Input::all(), $id );
```

Delete entry in Repository

```php
$this->repository->delete($id)
```
---
## Documentation standards and generation ##

This API is documented in confluence using [swagger](http://swagger.io/), in order for us to keep our documentation up to date and clean, we have a simple artisan command that will generate documentation for swagger based from annotation files that we may define. 

`php artisan l5-swagger:generate` 

This command is taken from [L5 Swagger Package on Github](https://github.com/DarkaOnLine/L5-Swagger)

# Where? How? and Why? #

To make this as easy as possible, I have included the annotations on the controller, when running `php artisan make:entity {Entity}` the controller that gets created will have these documentation annotations included with the standard boilerplate, these will need to be altered to match the requests that come in and out. 

The methods that are auto generated match the controller auto generation, any new functions will have to be manually added.

## Annotation breakdown ##

Annotations are meta-data that can be embedded in source code. Usually written within doc blocs, the annotations can define our endpoints and security measures for us to easily make fully documented API endpoints. 

What we want to achieve here is to generate a swagger file in JSON format.

To achieve this, we may build annotations that correlate to our API and generate the JSON using our artisan command. 
### Example ###
```
#!php
<?php

// Defining the routes
/**
 * @SWG\Get(
 *      path="/cities",
 *      operationId="get_all_cities",
 *      tags={"Cities"},
 *      summary="Get list of all cities",
 *      description="Returns list of cities",
 *      @SWG\Response(
 *          response=200,
 *          description="successful operation",
 *     @SWG\Schema(
 *             type="array",
 *             @SWG\Items(ref="#/definitions/City")
 *         ),
 *       ),
 *  )
 *
 */

/**
 * @SWG\Get(
 *      path="/cities/1",
 *      operationId="get_single_city",
 *      tags={"Cities"},
 *      summary="Get single city",
 *      description="Returns a single city",
 *      @SWG\Response(
 *          response=200,
 *          description="successful operation",
 *          @SWG\Schema(
 *             type="array",
 *             @SWG\Items(ref="#/definitions/City")
 *         ),
 *       )
 * )
 *
 */
```

Given the above annotations, when we run `php artisan l5-swagger:generate` we will get a JSON formatted file that will include the routes as swagger documentation.

The city Endpoints:

![City Endpoints](https://bitbucket.org/repo/Bggxejd/images/816270752-Screen%20Shot%202017-05-04%20at%202.35.22%20PM.png)

![City Endpoints Expanded](https://bitbucket.org/repo/Bggxejd/images/2820044863-Screen%20Shot%202017-05-04%20at%202.35.36%20PM.png)








