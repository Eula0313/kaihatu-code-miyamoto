const trainerData = {
    1: { name: "サトシ", image: "https://i.pinimg.com/736x/67/53/aa/6753aad71ea75d2eab4cf6ce4a755d84.jpg" },
    2: { name: "カスミ", image: "https://www.pokemon.co.jp/ex/pika_vee/character/images/thumb_05_active.png" },
    3: { name: "タケシ", image: "https://www.pokemon.co.jp/ex/pika_vee/character/images/thumb_04_active.png" }
};

const pokemonData = {
    1: { name: "ピカチュウ", image: "https://zukan.pokemon.co.jp/zukan-api/up/images/index/5bb0cfd44302cd4df0c0c88d37457931.png" },
    2: { name: "スターミー", image: "https://zukan.pokemon.co.jp/zukan-api/up/images/index/774a4e2f78322771104d21572102731e.png" },
    3: { name: "イシツブテ", image: "https://zukan.pokemon.co.jp/zukan-api/up/images/index/02f5389ac72186e9b1b35274ec94dd46.png" }
};

document.addEventListener('DOMContentLoaded', () => {

    // サーバーサイドから渡された値を取得
    const trainerImageVal = document.getElementById('trainerIcon').getAttribute('data-trainer-id');
    const partnerPokemonId = document.getElementById('partnerPokemon').getAttribute('data-pokemon-id');

    const trainerIconSelect = document.getElementById('trainerIcon');
    const partnerPokemonSelect = document.getElementById('partnerPokemon');
    const trainerPreviewImage = document.getElementById('trainerIconPreview');
    const pokemonPreviewImage = document.getElementById('pokemonIconPreview');
    const trainerNameDisplay = document.getElementById('trainerNameDisplay');

    Object.entries(trainerData).forEach(([id, trainer]) => {
        const option = document.createElement('option');
        option.value = id;
        option.textContent = trainer.name;
        trainerIconSelect.appendChild(option);
    });

    Object.entries(pokemonData).forEach(([id, pokemon]) => {
        const option = document.createElement('option');
        option.value = id;
        option.textContent = pokemon.name;
        partnerPokemonSelect.appendChild(option);
    });

    trainerIconSelect.value = trainerImageVal;
    partnerPokemonSelect.value = partnerPokemonId;
    trainerPreviewImage.src = trainerData[trainerImageVal].image;
    pokemonPreviewImage.src = pokemonData[partnerPokemonId].image;
    trainerNameDisplay.textContent = trainerData[trainerImageVal].name;
    pokemonNameDisplay.textContent = pokemonData[partnerPokemonId].name;
});

document.getElementById('trainerIcon').addEventListener('change', function () {
    const selectedValue = this.value;
    document.getElementById('trainerIconPreview').src = trainerData[selectedValue]?.image || "/images/noImage.png";
    document.getElementById('trainerNameDisplay').textContent = trainerData[selectedValue]?.name || "トレーナー名";
});

document.getElementById('partnerPokemon').addEventListener('change', function () {
    const selectedValue = this.value;
    document.getElementById('pokemonIconPreview').src = pokemonData[selectedValue]?.image || "/images/noImage.png";
    document.getElementById('pokemonNameDisplay').textContent = pokemonData[selectedValue]?.name || "ポケモン名";
});
