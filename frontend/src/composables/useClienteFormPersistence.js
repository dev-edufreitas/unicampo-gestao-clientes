import { onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';

/**
 * Persistência do formulário de cliente:
 * - Limpa ou restaura dados conforme a rota
 * - Carrega cliente existente e lista de profissões
 */
export function useClienteFormPersistence() {
  const route  = useRoute();
  const router = useRouter();
  const store  = useStore();

  onMounted(async () => {
    const isNovoCliente = !route.params.id;
    const limpar        = route.query.clean === '1';

    if (isNovoCliente && limpar === '1') {
      await store.dispatch('cliente/resetForm');
      router.replace({ path: route.path });
    } else {
      await store.dispatch('cliente/loadSavedStep');
      await store.dispatch('cliente/loadSavedFormData');
    }

    if (!isNovoCliente) {
      try {
        await store.dispatch('cliente/fetchCliente', route.params.id);
      } catch (error) {
        console.error('Erro ao carregar cliente:', error);
      }
    }

    try {
      await store.dispatch('profissao/fetchProfissoes');
    } catch (error) {
      console.error('Erro ao carregar profissões:', error);
    }
  });
}
