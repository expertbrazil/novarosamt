@extends('layouts.admin')

@php
    $title = 'Editar Cliente';
@endphp

@section('content')
@if($errors->any())
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.customers.update', $customer) }}" class="bg-white rounded-lg shadow p-6 max-w-3xl">
    @csrf
    @method('PUT')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Pessoa *</label>
            <select name="person_type" id="person_type" class="w-full px-3 py-2 border rounded-lg" onchange="togglePessoa()">
                <option value="PF" {{ old('person_type', $customer->person_type) == 'PF' ? 'selected' : '' }}>Física</option>
                <option value="PJ" {{ old('person_type', $customer->person_type) == 'PJ' ? 'selected' : '' }}>Jurídica</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
            <input type="text" name="name" value="{{ old('name', $customer->name) }}" required class="w-full px-3 py-2 border rounded-lg">
        </div>
        <div id="birthdate_group">
            <label class="block text-sm font-medium text-gray-700 mb-1">Data de Nascimento</label>
            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', optional($customer->birth_date)->format('Y-m-d')) }}" class="w-full px-3 py-2 border rounded-lg">
        </div>
        <div id="cpf_group">
            <label class="block text-sm font-medium text-gray-700 mb-1">CPF (11 dígitos) *</label>
            <input type="text" name="cpf" id="cpf" value="{{ old('cpf', $customer->cpf) }}" maxlength="14" required class="w-full px-3 py-2 border rounded-lg" placeholder="000.000.000-00">
            <p id="cpf_error" class="text-red-600 text-sm mt-1 hidden">CPF inválido.</p>
        </div>
        <div id="cnpj_group" class="hidden">
            <label class="block text-sm font-medium text-gray-700 mb-1">CNPJ (14 dígitos) *</label>
            <input type="text" name="cnpj" id="cnpj" value="{{ old('cnpj', $customer->cnpj) }}" maxlength="18" class="w-full px-3 py-2 border rounded-lg" placeholder="00.000.000/0000-00">
            <p id="cnpj_error" class="text-red-600 text-sm mt-1 hidden">CNPJ inválido.</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $customer->email) }}" class="w-full px-3 py-2 border rounded-lg">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Telefone</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone', $customer->phone) }}" class="w-full px-3 py-2 border rounded-lg" placeholder="(99) 99999-9999">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">CEP</label>
            <input type="text" name="cep" id="cep" value="{{ old('cep', $customer->cep) }}" maxlength="9" class="w-full px-3 py-2 border rounded-lg" placeholder="00000-000" onblur="buscarCEP(this.value)">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Logradouro</label>
            <input type="text" name="street" id="street" value="{{ old('street', $customer->street) }}" class="w-full px-3 py-2 border rounded-lg">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Número</label>
            <input type="text" name="number" id="number" value="{{ old('number', $customer->number) }}" class="w-full px-3 py-2 border rounded-lg">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Complemento</label>
            <input type="text" name="complement" id="complement" value="{{ old('complement', $customer->complement) }}" class="w-full px-3 py-2 border rounded-lg">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Bairro</label>
            <input type="text" name="district" id="district" value="{{ old('district', $customer->district) }}" class="w-full px-3 py-2 border rounded-lg">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Cidade</label>
            <input type="text" name="city" id="city" value="{{ old('city', $customer->city) }}" class="w-full px-3 py-2 border rounded-lg">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">UF</label>
            <input type="text" name="state" id="state" value="{{ old('state', $customer->state) }}" maxlength="2" class="w-full px-3 py-2 border rounded-lg">
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Endereço (completo)</label>
            <textarea name="address" id="address" rows="2" class="w-full px-3 py-2 border rounded-lg">{{ old('address', $customer->address) }}</textarea>
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
            <textarea name="notes" rows="3" class="w-full px-3 py-2 border rounded-lg">{{ old('notes', $customer->notes) }}</textarea>
        </div>
        <div class="flex items-center">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $customer->is_active) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 border-gray-300 rounded">
            <label class="ml-2 text-sm text-gray-700">Cliente ativo</label>
        </div>
    </div>

    <div class="mt-6 flex justify-end space-x-4">
        <a href="{{ route('admin.customers.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Cancelar</a>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Atualizar</button>
    </div>
</form>
<script>
function limpaCamposEndereco(){
    document.getElementById('street').value = '';
    document.getElementById('district').value = '';
    document.getElementById('city').value = '';
    document.getElementById('state').value = '';
}
function buscarCEP(valor){
    const cep = valor.replace(/\D/g,'');
    if (cep.length !== 8) { return; }
    fetch('https://viacep.com.br/ws/' + cep + '/json/')
        .then(r => r.json())
        .then(data => {
            if(data.erro){ return; }
            document.getElementById('cep').value = data.cep;
            document.getElementById('street').value = data.logradouro || '';
            document.getElementById('district').value = data.bairro || '';
            document.getElementById('city').value = data.localidade || '';
            document.getElementById('state').value = data.uf || '';
            atualizarEnderecoCompleto();
        })
        .catch(() => {});
}
function atualizarEnderecoCompleto(){
    const partes = [
        document.getElementById('street').value,
        document.getElementById('number').value,
        document.getElementById('complement').value,
        document.getElementById('district').value,
        document.getElementById('city').value,
        document.getElementById('state').value,
        document.getElementById('cep').value,
    ].filter(Boolean);
    document.getElementById('address').value = partes.join(', ');
}
['street','number','complement','district','city','state','cep'].forEach(id=>{
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', atualizarEnderecoCompleto);
});
// Máscara telefone
const phoneInput = document.getElementById('phone');
if (phoneInput) {
    // aplica máscara inicial se vier sem formatação
    (function(){
        let v = phoneInput.value || '';
        v = v.replace(/\D/g,'');
        if (v.length === 10) phoneInput.value = v.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
        else if (v.length === 11) phoneInput.value = v.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
    })();
    phoneInput.addEventListener('input', function(){
        let v = this.value.replace(/\D/g,'').slice(0,11);
        if (v.length > 10) {
            this.value = v.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        } else if (v.length > 6) {
            this.value = v.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
        } else if (v.length > 2) {
            this.value = v.replace(/(\d{2})(\d{0,5})/, '($1) $2');
        } else {
            this.value = v.replace(/(\d{0,2})/, '($1');
        }
    });
}
// Normaliza antes de enviar
document.querySelector('form').addEventListener('submit', function(e){
    if (phoneInput) phoneInput.value = phoneInput.value.replace(/\D/g,'');
    // bloqueia envio se CPF/CNPJ inválidos
    const tipo = document.getElementById('person_type').value;
    let invalid=false;
    if (tipo==='PF'){
        if (document.getElementById('cpf') && !isValidCPF(document.getElementById('cpf').value)) invalid=true;
    } else {
        if (document.getElementById('cnpj') && !isValidCNPJ(document.getElementById('cnpj').value)) invalid=true;
    }
    if (invalid) {
        e.preventDefault();
        const cpfI=document.getElementById('cpf'); if(cpfI) cpfI.dispatchEvent(new Event('input'));
        const cnpjI=document.getElementById('cnpj'); if(cnpjI) cnpjI.dispatchEvent(new Event('input'));
    }
});
function togglePessoa(){
    const tipo = document.getElementById('person_type').value;
    const cpfGroup = document.getElementById('cpf_group');
    const cnpjGroup = document.getElementById('cnpj_group');
    const birthGroup = document.getElementById('birthdate_group');
    if (tipo === 'PJ'){
        cnpjGroup.classList.remove('hidden');
        cpfGroup.classList.add('hidden');
        if (birthGroup) birthGroup.classList.add('hidden');
    } else {
        cpfGroup.classList.remove('hidden');
        cnpjGroup.classList.add('hidden');
        if (birthGroup) birthGroup.classList.remove('hidden');
    }
}
togglePessoa();
// ===== Máscaras e validações CPF/CNPJ =====
function onlyDigits(v){return (v||'').replace(/\D/g,'');}
function maskCPF(v){v=onlyDigits(v).slice(0,11);return v.replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d{1,2})$/,'$1-$2');}
function maskCNPJ(v){v=onlyDigits(v).slice(0,14);return v.replace(/(\d{2})(\d)/,'$1.$2').replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d)/,'$1/$2').replace(/(\d{4})(\d{1,2})$/,'$1-$2');}
function isValidCPF(cpf){cpf=onlyDigits(cpf);if(cpf.length!==11||/(\d)\1{10}/.test(cpf))return false;for(let t=9;t<11;t++){let d=0;for(let c=0;c<t;c++){d+=cpf[c]*((t+1)-c);}d=((10*d)%11)%10;if(cpf[t]!=d)return false;}return true;}
function isValidCNPJ(cnpj){cnpj=onlyDigits(cnpj);if(cnpj.length!==14||/(\d)\1{13}/.test(cnpj))return false;let t=[5,4,3,2,9,8,7,6,5,4,3,2],s=0;for(let i=0;i<12;i++)s+=cnpj[i]*t[i];let r=s%11,d1=(r<2)?0:11-r;t=[6,5,4,3,2,9,8,7,6,5,4,3,2];s=0;for(let i=0;i<13;i++)s+=cnpj[i]*t[i];r=s%11;let d2=(r<2)?0:11-r;return (cnpj[12]==d1 && cnpj[13]==d2);} 
const cpfInput=document.getElementById('cpf');
const cpfError=document.getElementById('cpf_error');
if(cpfInput){
  const applyCpf=()=>{cpfInput.value=maskCPF(cpfInput.value);const ok=isValidCPF(cpfInput.value);cpfInput.classList.toggle('border-red-500',!ok);cpfError.classList.toggle('hidden',ok);};
  cpfInput.addEventListener('input',applyCpf);cpfInput.addEventListener('blur',applyCpf);applyCpf();
}
const cnpjInput=document.getElementById('cnpj');
const cnpjError=document.getElementById('cnpj_error');
if(cnpjInput){
  const applyCnpj=()=>{cnpjInput.value=maskCNPJ(cnpjInput.value);const ok=isValidCNPJ(cnpjInput.value);cnpjInput.classList.toggle('border-red-500',!ok);cnpjError.classList.toggle('hidden',ok);};
  cnpjInput.addEventListener('input',applyCnpj);cnpjInput.addEventListener('blur',applyCnpj);applyCnpj();
}
// Sanitiza CPF/CNPJ antes de enviar
document.querySelector('form').addEventListener('submit',function(){
  if(cpfInput) cpfInput.value=onlyDigits(cpfInput.value);
  if(cnpjInput) cnpjInput.value=onlyDigits(cnpjInput.value);
});
</script>
@endsection


