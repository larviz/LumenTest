<?php

    namespace App\Http\Controllers;

    use App\Usuario;
    use Illuminate\Database\Eloquent\ModelNotFoundException;
    use Illuminate\Http\Request;
    use Illuminate\Validation\Rule;
    use Illuminate\Validation\ValidationException;

    class UserController extends Controller
    {
        protected $guard_name = 'api';
        private $sin_permisos = "Usuario no Autorizado";
        private $no_content = "No se han encontrado resultados";

        /**
         * Create a new controller instance.
         *
         * @return void
         */
        public function __construct ()
        {
        }

        public function index (Request $request)
        {
            if ($request->isJson()) {
                $usuarios = Usuario::all();

                return response()->json($usuarios, 200);
            }
            else {
                return response()->json(['Error' => $this->sin_permisos], 401, []);
            }
        }

        public function getUser (Request $request, $id)
        {
            if ($request->isJson()) {
                try {
                    $usuario = Usuario::findOrFail($id);
                    $usuario = $this->UserFormat($usuario);

                    return response()->json($usuario, 200);
                } catch (ModelNotFoundException $e) {
                    return response()->json(['Error' => $this->no_content . $e], 406);
                }
            }
            else {
                return response()->json(['Error' => $this->sin_permisos], 401, []);
            }
        }

        private function UserFormat ($usuario)
        {
            return $usuario;
        }

        public function create (Request $request)
        {
            if ($request->isJson()) {
                try {
                    $this->validate($request, $this->rules(), $this->messages(), $this->attributes());
                } catch (ValidationException $e) {
                    return response()->json(['Error' => $e->validator->errors()->first()], 406);
                }
                $data = $request->all();
                $ifExists = Usuario::where('ife', $data['ife'])->count();
                if ($ifExists === 0) {
                    $usuario = Usuario::create([
                        'nombre' => $data['nombre'],
                        'edad' => $data['edad'],
                        'ife' => $data['ife'],
                        'status' => $data['status']
                    ]);
                    $usuario->id;
                    if ($request->has('foto_user')) {
                        $this->subirFoto($request, $usuario);
                    }
                    else {
                        $usuario->save();
                    }
                    $usuario = $this->UserFormat($usuario);

                    return response()->json($usuario, 201);
                }
                else {
                    return response()->json(['Error' => 'El IFE ya se encuentra registrado'], 406, []);
                }
            }
            else {
                return response()->json(['Error' => $this->sin_permisos], 401, []);
            }
        }

        public function rules ($id = null)
        {
            if ($id != null) {
                return [
                    'nombre' => 'required|min:5|max:255',
                    'edad' => 'required',
                    'ife' => 'required|' . Rule::unique('usuarios')->ignore($id),
                    'status' => 'required',
                ];
            }

            return [
                'nombre' => 'required|min:5|max:255',
                'edad' => 'required',
                'ife' => 'required|unique:usuarios',
                'status' => 'required',
            ];
        }

        public function messages ()
        {
            return [
                'nombre.required' => 'Por favor ingresa tu :attribute',
                'nombre.min' => 'El :attribute debe ser de al menos 5 caracteres',
                'nombre.max' => 'El :attribute debe ser menor de 255 caracteres',
                'edad.required' => 'Por favor ingresa tu :attribute',
                'ife.required' => 'Por favor ingresa tu :attribute',
                'ife.unique' => 'El :attribute que haz ingresado ya se encuentra registrado',
                'status.required' => 'Por favor llena el campo :attribute',
            ];
        }

        public function attributes ()
        {
            return [
                'nombre' => 'nombre completo',
                'edad' => 'edad',
                'ife' => 'IFE / INE',
                'status' => 'estatus',
            ];
        }

        private function subirFoto (Request $request, $user)
        {
            if ($request->hasFile('foto_taxi')) {
                $hash = $user->id . date("Ymdhis");
                $original_filename = $request->file('foto_user')->getClientOriginalName();
                $original_filename_arr = explode('.', $original_filename);
                $file_ext = end($original_filename_arr);
                $destination_path = './upload/foto/';
                $image = 'U-' . $hash . '.' . $file_ext;
                if ($request->file('foto_taxi')->move($destination_path, $image)) {
                    $user->foto_taxi = '/upload/taxi/' . $image;
                    $user->save();
                    
                }
                else {
                    return response()->json(['Error' => 'No se pudo subir la foto'], 206);
                }
            }
        }

        public function update (Request $request, $id)
        {
            if ($request->isJson()) {

                try {
                    try {
                        $this->validate($request, $this->rules(), $this->messages(), $this->attributes());
                    } catch (ValidationException $e) {
                        return response()->json(['Error' => $e->validator->errors()->first()], 204);
                    }
                    $data = $request->all();
                    $usuario = Usuario::findOrFail($id);
                    $usuario->nombre = $data['nombre'];
                    $usuario->edad = $data['edad'];
                    $usuario->ife = $data['ife'];
                    $usuario->status = $data['status'];
                    if ($request->has('foto_user')) {
                        $this->subirFoto($request, $usuario);
                    }
                    else {
                        $usuario->save();
                    }
                    $usuario = $this->UserFormat($usuario);

                    return response()->json($usuario, 200);
                } catch (ModelNotFoundException $e) {
                    return response()->json(['Error' => $this->no_content], 204);
                }
            }
            else {
                return response()->json(['Error' => $this->sin_permisos], 401, []);
            }
        }

        public function delete (Request $request, $id)
        {
            if ($request->isJson()) {
                try {
                    $usuario = Usuario::findOrFail($id);
                    $usuario->delete();

                    return response()->json(['Success' => 'Usuario eliminado'], 200);
                } catch (ModelNotFoundException $e) {
                    return response()->json(['Error' => $this->no_content], 204);
                }
            }
            else {
                return response()->json(['Error' => $this->sin_permisos], 401, []);
            }
        }
    }
