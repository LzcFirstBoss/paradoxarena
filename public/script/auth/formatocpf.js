const cpfInput = document.getElementById('cpf');

// Impede a digitação de caracteres que não sejam números
cpfInput.addEventListener('keypress', function(e) {
  // Permite teclas de controle (backspace, delete, etc)
  if (e.key.length > 1) return;
  if (!/[0-9]/.test(e.key)) {
    e.preventDefault();
  }
});

// Formata o CPF enquanto o usuário digita ou cola o valor
cpfInput.addEventListener('input', function() {
  let cpf = this.value.replace(/\D/g, ''); // Remove qualquer caractere que não seja número

  // Limita a 11 dígitos
  if(cpf.length > 11) cpf = cpf.substring(0, 11);

  // Aplica a formatação: 000.000.000-00
  cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
  cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
  cpf = cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

  this.value = cpf;
});

    // Seleciona todos os botões de toggle de senha
    document.querySelectorAll('.toggle-password').forEach(function(toggleSpan) {
        // Encontra o input de senha correspondente (assumindo que ele está logo antes do span)
        const inputField = toggleSpan.parentElement.querySelector('input');
        
        toggleSpan.addEventListener('click', function() {
          // Alterna o tipo do input e o ícone
          if (inputField.type === "password") {
            inputField.type = "text";
            toggleSpan.textContent = "visibility_off";
          } else {
            inputField.type = "password";
            toggleSpan.textContent = "visibility";
          }
        });
      });

      document.getElementById("myForm").addEventListener("submit", function(){
        const btn = document.getElementById("submitBtn");
        btn.disabled = true;
        btn.classList.add("loading");
        // Altera o texto do botão e adiciona o spinner
        btn.innerHTML = "<span class='spinner'></span>";
      });


      const pixType = document.getElementById("pixType");
      const pixKey = document.getElementById("pixKey");
      
      // Função para formatar CPF: 000.000.000-00
      function formatCPF(value) {
        let cpf = value.replace(/\D/g, '').substring(0, 11);
        cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
        cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
        cpf = cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        return cpf;
      }
      
      // Função para formatar CNPJ: 00.000.000/0000-00
      function formatCNPJ(value) {
        let cnpj = value.replace(/\D/g, '').substring(0, 14);
        cnpj = cnpj.replace(/^(\d{2})(\d)/, '$1.$2');
        cnpj = cnpj.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
        cnpj = cnpj.replace(/\.(\d{3})(\d)/, '.$1/$2');
        cnpj = cnpj.replace(/(\d{4})(\d)/, '$1-$2');
        return cnpj;
      }
      
      // Função para formatar Telefone:
      // - Para 10 dígitos: (XX) XXXX-XXXX
      // - Para 11 dígitos: (XX) XXXXX-XXXX
      function formatTelefone(value) {
        let phone = value.replace(/\D/g, '').substring(0, 11);
        if (phone.length <= 10) {
          phone = phone.replace(/(\d{2})(\d)/, '($1) $2');
          phone = phone.replace(/(\d{4})(\d)/, '$1-$2');
        } else {
          phone = phone.replace(/(\d{2})(\d)/, '($1) $2');
          phone = phone.replace(/(\d{5})(\d)/, '$1-$2');
        }
        return phone;
      }
      
      // Atualiza o placeholder, formatação e estado do input conforme o tipo selecionado
      function updatePlaceholderAndFormat() {
        const selected = pixType.value;
        
        switch(selected) {
          case "cpf":
            pixKey.placeholder = "Digite seu CPF (somente números)";
            pixKey.disabled = false;
            pixKey.value = formatCPF(pixKey.value);
            break;
          case "cnpj":
            pixKey.placeholder = "Digite seu CNPJ (somente números)";
            pixKey.disabled = false;
            pixKey.value = formatCNPJ(pixKey.value);
            break;
          case "telefone":
            pixKey.placeholder = "Digite seu telefone (somente números)";
            pixKey.disabled = false;
            pixKey.value = formatTelefone(pixKey.value);
            break;
          case "email":
            pixKey.placeholder = "Digite seu email";
            pixKey.disabled = false;
            break;
          case "aleatoria":
            pixKey.placeholder = "Chave aleatória";
            pixKey.disabled = false;
            break;
          default:
            pixKey.placeholder = "Digite a chave PIX";
            pixKey.disabled = false;
        }
      }
      
      // Atualiza a formatação enquanto o usuário digita, conforme a seleção atual
      pixKey.addEventListener("input", function() {
        const selected = pixType.value;
        let formatted = this.value;
        if (selected === "cpf") {
          formatted = formatCPF(formatted);
        } else if (selected === "cnpj") {
          formatted = formatCNPJ(formatted);
        } else if (selected === "telefone") {
          formatted = formatTelefone(formatted);
        }
          // Para email e aleatória não aplica formatação
          this.value = formatted;
      });
      
      // Atualiza o placeholder, formatação e habilitação do input ao mudar o select
      pixType.addEventListener("change", updatePlaceholderAndFormat);
      
      // Inicializa os valores e placeholder
      updatePlaceholderAndFormat(); 

  