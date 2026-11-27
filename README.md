# Open Side Cart for WooCommerce

Um carrinho lateral (drawer) livre e de código aberto para WooCommerce, com todos os
recursos liberados e sem ativação de licença, atualizador remoto ou telemetria.

É um fork do Woocommerce Side Cart Premium 4.9.1, da XootiX, redistribuído sob a
GPL. Veja os detalhes em [CREDITS.md](CREDITS.md). Sem vínculo com a XootiX.

## Instalação

**[Baixar o plugin (zip da última versão)](https://github.com/fredpiuma/open-side-cart/releases/latest/download/open-side-cart.zip)**

Esse zip tem só os arquivos do plugin. Não use o "Source code (zip)" do GitHub para instalar: ele traz arquivos de desenvolvimento e a pasta fica com o nome errado.

### Pelo painel do WordPress

1. Baixe o zip pelo link acima.
2. Em **Plugins → Adicionar novo → Enviar plugin**, escolha o arquivo e clique em **Instalar agora**.
3. Ative o **Open Side Cart for WooCommerce** e abra o menu **Side Cart** para configurar.

Para atualizar, envie o zip da versão nova da mesma forma e confirme a substituição da versão instalada.

### Pelo WP-CLI

```bash
wp plugin install https://github.com/fredpiuma/open-side-cart/releases/latest/download/open-side-cart.zip --force --activate
```

O mesmo comando instala e atualiza: o `--force` substitui a versão instalada pela última, já que o plugin não tem atualização automática pelo painel.

Requer WordPress 6.5+ (usa o cabeçalho `Requires Plugins`) e WooCommerce.

Veja no [CHANGELOG.md](CHANGELOG.md) o que mudou em cada versão.

## Recursos

- Gaveta de carrinho deslizante, cesta flutuante e shortcode de carrinho
- Barra de frete grátis e barra de progresso de recompensas (descontos, brindes, metas)
- Formulário de cupom, calculadora de frete e botões de pagamento no carrinho
- Produtos sugeridos, visualização rápida (inclusive de produtos variáveis) e edição de variações no carrinho
- Salvar para depois (com integração opcional com o plugin Easy Login)
- Temas de botão, layouts de cabeçalho/corpo/rodapé e pré-visualização ao vivo nas configurações

## Traduções

O plugin vem com a tradução para **português do Brasil (pt_BR)**, carregada
automaticamente quando o idioma do site é Português do Brasil.

Os arquivos de tradução ficam em `languages/`:

- `open-side-cart.pot`: modelo com todos os textos traduzíveis;
- `open-side-cart-pt_BR.po`: fonte da tradução, editável no Poedit ou no Loco Translate;
- `open-side-cart-pt_BR.mo` e `open-side-cart-pt_BR.l10n.php`: arquivos compilados que o WordPress lê.

Para acrescentar um idioma ou ajustar um texto no seu site, use o Loco Translate. Mantenha
o arquivo no local "Custom" ou "System", para que as atualizações não o sobrescrevam; esses
locais têm prioridade sobre os arquivos que vêm com o plugin. Contribuições de novos
idiomas são bem-vindas como pull requests.

## Vindo do plugin da XootiX

Nomes de opções, hooks, classes CSS e caminhos de templates foram renomeados, então:

- As configurações do plugin gratuito ou premium da XootiX **não** são importadas; configure o fork de novo.
- Código personalizado que usa hooks `xoo_wsc_*` ou classes `.xoo-wsc-*` precisa ser atualizado para `osc_*` / `.osc-*`.
- As sobrescritas de templates ficam em `seu-tema/templates/open-side-cart/`.

O fork pode ficar ativo junto com os plugins oficiais, mas não é recomendado usar dois carrinhos laterais.

## Licença

[GPL-2.0-or-later](LICENSE).

---

### Precisa de uma solução sob medida para a sua loja?

Desenvolvo plugins, integrações e melhorias para WordPress e WooCommerce, do checkout ao pós-venda. Me conte o que você precisa e vamos encontrar o melhor caminho para o seu projeto.

**Frederico de Castro** · Senior Web Developer & Software Architect

[![Fale comigo no WhatsApp](https://img.shields.io/badge/Fale_comigo_no_WhatsApp-25D366?style=for-the-badge&logo=whatsapp&logoColor=white)](https://wa.me/5511914636944)
[![E-mail](https://img.shields.io/badge/E--mail-D14836?style=for-the-badge&logo=gmail&logoColor=white)](mailto:contato@fredericodecastro.com.br)
[![Site](https://img.shields.io/badge/Site-1F2937?style=for-the-badge&logo=googlechrome&logoColor=white)](https://www.fredericodecastro.com.br/links/)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-0A66C2?style=for-the-badge)](https://www.linkedin.com/in/fredericodecastro/)
[![Telegram](https://img.shields.io/badge/Telegram-2CA5E0?style=for-the-badge&logo=telegram&logoColor=white)](https://t.me/fredericomdecastro)
