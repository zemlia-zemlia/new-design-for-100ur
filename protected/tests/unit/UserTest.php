<?php

class UserTest extends CDbTestCase
{
    
    public $fixtures=array(
        'users'=>'User',
    );
    
    // тест создания и сохранения 
    public function testCreateYurist()
    {
        $user = new User;
        $user->setScenario('createJurist');
        
        $user->setAttributes([
            'name'  =>  "Вася",
            'name2'  =>  "Петрович",
            'lastName'  =>  "Пупкин",
            'email'  =>  "v@pupkin.ru",
            'role'  =>  User::ROLE_JURIST,
            'townId'  =>  598,
            'agree'  =>  1,
            'password'  =>  '12345',
            'password2'  =>  '12345',
        ]);
        
        $user->validate();
       
        $this->assertTrue(empty($user->errors));
        
        $this->assertTrue($user->save());
        
                
        $newUserId = $user->id;
        
        $newUser = User::model()->findByPk($newUserId);
        $this->assertTrue($newUser instanceof User);
        $this->assertEquals(1, $newUser->id);
        
        $this->assertEquals('Пупкин&nbsp;В.П.', $newUser->getShortName());
        
        $this->assertEquals(128, mb_strlen($newUser->password, 'utf-8'));
        $this->assertEquals('Москва', $newUser->townName);
        $this->assertEquals(0, $newUser->active100);
    }
    
    public function testChangePassword()
    {
        print_r($this->users('yurist_1'));
        $user = User::model()->findByPk(13);
        $this->assertTrue($user instanceof User);
        
        $oldPassword = $user->password;
        
        $user->changePassword('2847623864826486');
        $this->assertNotEquals($oldPassword, $user->password);
    }
}