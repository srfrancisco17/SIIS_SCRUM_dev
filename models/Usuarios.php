<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
/** 
 * This is the model class for table "usuarios". 
 * 
 * @property integer $usuario_id
 * @property string $num_documento
 * @property string $tipo_documento
 * @property string $nombres
 * @property string $apellidos
 * @property string $descripcion
 * @property string $correo
 * @property string $telefono
 * @property string $contrasena
 * @property string $departamento
 * @property string $tipo_usuario
 * @property string $color
 * @property integer $estado
 * @property string $skin
 * 
 * @property ComitesAsistentes[] $comitesAsistentes
 * @property Comites[] $comites
 * @property Requerimientos[] $requerimientos
 * @property SprintUsuarios[] $sprintUsuarios
 * @property Sprints[] $sprints
 * @property Departamentos $departamento0
 * @property TiposDocumentos $tipoDocumento
 * @property TiposUsuarios $tipoUsuario
 */
class Usuarios extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
 /**
 * @inheritdoc
 */
    
/*
 * Constantes De Acuerdo Al Tipo De Usuario
 */
    const USUARIO_SCRUM_MASTER = 1;
    const USUARIO_DEVELOPER = 2;
    const USUARIO_PRODUCT_OWNER = 3;
    
/*
 * Constante Del Estado De Un Usuario
 */

    const ESTADO_INACTIVO = 0;
    const ESTADO_ACTIVO = 1;
    
    public $new_password;
    public $password_repeat;
    
    public $prueba;
    
    public static function tableName()
    {
        return 'usuarios';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['new_password', 'string', 'min' => 6],
            [['num_documento', 'tipo_documento', 'nombres', 'apellidos', 'contrasena', 'tipo_usuario'], 'required'],
            [['tipo_usuario'], 'number'],
            [['estado'], 'integer'],
            [['estado'], 'default', 'value' => null],
            [['num_documento'], 'integer', 'message' => 'Documento debe ser numerico'],
            [['tipo_documento'], 'string', 'max' => 3],
            [['nombres', 'apellidos', 'color'], 'string', 'max' => 30],
            [['descripcion'], 'string', 'max' => 60],
            [['correo'], 'string', 'max' => 50],
            [['telefono'], 'string', 'max' => 10],
            [['contrasena'], 'string', 'max' => 225],
            [['departamento'], 'string', 'max' => 4],
            [['skin'], 'string', 'max' => 20],
            [['num_documento', 'tipo_documento'], 'unique', 'targetAttribute' => ['num_documento', 'tipo_documento'], 'message' => 'El tipo de documento y el documento ya existen!'],
            [['departamento'], 'exist', 'skipOnError' => true, 'targetClass' => Departamentos::className(), 'targetAttribute' => ['departamento' => 'departamento_id']],
            [['tipo_documento'], 'exist', 'skipOnError' => true, 'targetClass' => TiposDocumentos::className(), 'targetAttribute' => ['tipo_documento' => 'documento_id']],
            [['tipo_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => TiposUsuarios::className(), 'targetAttribute' => ['tipo_usuario' => 'tipo_usuario_id']],
            ['password_repeat', 'compare', 'compareAttribute' => 'new_password'],
            ['new_password', 'compare', 'compareAttribute' => 'password_repeat'], 
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'usuario_id' => 'Usuario ID',
            'num_documento' => 'Documento',
            'tipo_documento' => 'Tipo Documento',
            'nombres' => 'Nombres',
            'apellidos' => 'Apellidos',
            'descripcion' => 'Descripcion',
            'correo' => 'Correo',
            'telefono' => 'Telefono',
            'contrasena' => 'Contrasena',
            'new_password' => 'Nueva Contraseña',
            'password_repeat' => 'Repetir Contraseña',
            'departamento' => 'Departamento',
            'tipo_usuario' => 'Tipo Usuario',
            'color' => 'Color',
            'estado' => 'Estado',
            'skin' => 'Skin',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    
    public function getnombreCompleto()
    {
        return $this->nombres.' '.$this->apellidos;
    }
    
    public function getComitesAsistentes()
    {
        return $this->hasMany(ComitesAsistentes::className(), ['usuario_id' => 'usuario_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComites()
    {
        return $this->hasMany(Comites::className(), ['comite_id' => 'comite_id'])->viaTable('comites_asistentes', ['usuario_id' => 'usuario_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequerimientos()
    {
        return $this->hasMany(Requerimientos::className(), ['usuario_solicita' => 'usuario_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSprintUsuarios()
    {
        return $this->hasMany(SprintUsuarios::className(), ['usuario_id' => 'usuario_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSprints()
    {
        return $this->hasMany(Sprints::className(), ['sprint_id' => 'sprint_id'])->viaTable('sprint_usuarios', ['usuario_id' => 'usuario_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartamento0()
    {
        return $this->hasOne(Departamentos::className(), ['departamento_id' => 'departamento']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoDocumento()
    {
        return $this->hasOne(TiposDocumentos::className(), ['documento_id' => 'tipo_documento']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoUsuario()
    {
        return $this->hasOne(TiposUsuarios::className(), ['tipo_usuario_id' => 'tipo_usuario']);
    }
    
    // ---------LISTAS------------
    public static function getListaDepartamentos() {
        $opciones = Departamentos::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'departamento_id', 'descripcion');
    }
    public static function getListaTiposUsuarios() {
        $opciones = TiposUsuarios::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'tipo_usuario_id', 'descripcion');
    }
    
    public static function getListaTipoDocumentos() {
        $opciones = TiposDocumentos::find()->asArray()->all();
        return ArrayHelper::map($opciones, 'documento_id', 'descripcion');
    }
    
    public static function getListaDevelopers() {
        
        $opciones = Usuarios::find()->where(['estado' => 1])->andWhere(['tipo_usuario' => 2])->asArray()->all();
        return ArrayHelper::map($opciones, 'usuario_id', 'nombres');
    }
    
    //Metodos Abstractos de indentity interfaces
    
    public function getAuthKey()
    {
        return $this->auth_key;
    }
    
    public function getId()
    {
        return $this->usuario_id;
    }
    
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }
    
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        //return static::findOne(['access_token' => $token]);
        throw  new \yii\base\NotSupportedException;
    }
    
    public static function findByUsername($username){
        return self::findOne(['num_documento' => $username]);
    }
    
    
    public function validatePassword($password){
        
        //return $this->contrasena  === $password;
        
        // $2y$13$ojJ1rG1wGRBWXTHUotp6gOa0jM9dxSxrGQeBJX.OQlU4LLe6a6DAi
        
        
//        $validacion = Yii::$app->security->validatePassword($password, '$2y$13$APasiVAPvJsieLFsSgAaROl/VY/hs1kS/sZ0/oMSgXUvw/fXUQSjq');
//        
//        var_dump($validacion);
//        exit();
//        
        return Yii::$app->security->validatePassword($password, $this->contrasena);
    }
    
    
    public function setPassword($password)
    {
        $this->contrasena = Yii::$app->security->generatePasswordHash($password);
    }

    // AUTENTICACION
    
    public static function tipoUsuarioArreglo($arreglo){
       return in_array(Yii::$app->user->identity->tipo_usuario, $arreglo); 
    }
    
    public static function estaActivo()
    {
        return Yii::$app->user->identity->estado == self::ESTADO_ACTIVO;
    }
    
    public function updatePassword($new_password) {
        $this->contrasena = Yii::$app->security->generatePasswordHash($new_password);
    }
    
   
}
