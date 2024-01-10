<div align="center">
<x-filament::fieldset style="width:40%;" align="center">
    <x-slot name="label">
        ©2024<a href="www.proyekan.com" class="hover:underline"> Proyekan.com™</a>. All Rights Reserved.
    </x-slot>
    
    <x-filament::input.wrapper suffix-icon="heroicon-m-envelope">
        <x-filament::input
            type="text"
            wire:model="name"
            placeholder="Enter Your E-mail Address"
            
        />
       
    </x-filament::input.wrapper>
    <br>
    
    <div style="justify-content:center; 
                display:flex;"
                >
        <div style="display:flex;">
            <x-filament::icon-button
            icon="icon-xicon"
            href="https://proyekan.com"
            tag="a"
            size="xl"
            label="Proyekan"
            tooltip="Go to Twitter Proyekan"
        />                               
        </div>
        <div style="margin-left: 10%;
                    display:flex;">
            <x-filament::icon-button
            icon="icon-youtube"
            href="https://proyekan.com"
            tag="a"
            size="xl"
            label="Proyekan"
            tooltip="Go to Youtube Proyekan"
        />
        </div>
        
    </div>
    
</x-filament::fieldset>
</div>