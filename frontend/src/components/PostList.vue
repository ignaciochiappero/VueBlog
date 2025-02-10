<template>
  <div class="post-list">
    <h2>POSTS</h2>
    <div v-if="isLoggedIn" class="create-post">
      <h3>Crear un post nuevo</h3>
      <form @submit.prevent="createPost">
        <div class="form-group">
          <label for="title">Título</label>
          <input v-model="newPost.title" type="text" id="title" placeholder="Ingresar un título" required>
        </div>
        <div class="form-group">
          <label for="content">Contenido</label>
          <textarea v-model="newPost.content" id="content" placeholder="¿Qué estás pensando?" required></textarea>
        </div>
        <button type="submit" :disabled="isCreatingPost">
          {{ isCreatingPost ? 'Creando post...' : 'Publicar' }}
        </button>
      </form>
    </div>
    <div class="posts">
      <div v-for="post in posts" :key="post.id" class="post">
        <h3>{{ post.title }}</h3>
        <p class="post-content">{{ post.content }}</p>
        <p class="post-meta">
          <span>Autor: {{ post.author }}</span>
          <span>Comentarios: {{ post.comment_count }}</span>
          <span>Likes: {{ post.like_count }}</span>
        </p>
        <div class="post-actions">
          <button @click="likePost(post.id)" v-if="isLoggedIn" class="btn-like" :disabled="isLiking[post.id]">
            <span class="icon">👍</span> {{ isLiking[post.id] ? 'Liking...' : 'Like' }}
          </button>
          <button @click="toggleComments(post.id)" class="btn-comments">
            <span class="icon">💬</span> {{ post.showComments ? 'Ocultar' : 'Mostrar' }} comentarios
          </button>
        </div>
        <div v-if="post.showComments" class="comments-section">
          <h4>Comments</h4>
          <ul class="comments-list">
            <li v-for="comment in post.comments" :key="comment.id" class="comment">
              <p>{{ comment.content }}</p>
              <small>By: {{ comment.author }}</small>
            </li>
          </ul>
          <form @submit.prevent="addComment(post.id)" class="add-comment-form">
            <textarea v-model="newComments[post.id]" placeholder="Agregá un comentario" required></textarea>
            <button type="submit" :disabled="isCommenting[post.id]">
              {{ isCommenting[post.id] ? 'Comentando...' : 'Agregar' }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import { useAuth } from '../utils/auth'

export default {
  setup() {
    const { isLoggedIn, user } = useAuth()
    const posts = ref([])
    const newPost = ref({ title: '', content: '' })
    const newComments = ref({})
    const isCreatingPost = ref(false)
    const isLiking = ref({})
    const isCommenting = ref({})

    const fetchPosts = async () => {
      try {
        const response = await axios.get('http://localhost/vue-php-blog/api/posts.php')
        posts.value = response.data.map(post => ({ ...post, showComments: false, comments: [] }))
      } catch (error) {
        console.error(error)
      }
    }

    const createPost = async () => {
      isCreatingPost.value = true
      try {
        await axios.post('http://localhost/vue-php-blog/api/posts.php', {
          user_id: user.value.id,
          title: newPost.value.title,
          content: newPost.value.content
        })
        newPost.value = { title: '', content: '' }
        await fetchPosts()
      } catch (error) {
        console.error(error)
      } finally {
        isCreatingPost.value = false
      }
    }

    const likePost = async (postId) => {
      isLiking.value[postId] = true
      try {
        await axios.post('http://localhost/vue-php-blog/api/likes.php', {
          post_id: postId,
          user_id: user.value.id
        })
        await fetchPosts()
      } catch (error) {
        console.error(error)
      } finally {
        isLiking.value[postId] = false
      }
    }

    const toggleComments = async (postId) => {
      const post = posts.value.find(p => p.id === postId)
      post.showComments = !post.showComments
      if (post.showComments && post.comments.length === 0) {
        try {
          const response = await axios.get(`http://localhost/vue-php-blog/api/comments.php?post_id=${postId}`)
          post.comments = response.data
        } catch (error) {
          console.error(error)
        }
      }
    }

    const addComment = async (postId) => {
      isCommenting.value[postId] = true
      try {
        await axios.post('http://localhost/vue-php-blog/api/comments.php', {
          post_id: postId,
          user_id: user.value.id,
          content: newComments.value[postId]
        })
        newComments.value[postId] = ''
        await toggleComments(postId)
      } catch (error) {
        console.error(error)
      } finally {
        isCommenting.value[postId] = false
      }
    }

    onMounted(fetchPosts)

    return {
      posts,
      newPost,
      newComments,
      isLoggedIn,
      isCreatingPost,
      isLiking,
      isCommenting,
      createPost,
      likePost,
      toggleComments,
      addComment
    }
  }
}
</script>

<style scoped>
.post-list {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

h2, h3 {
  margin-bottom: 1rem;
  color: var(--color-primary);
}

.create-post {
  background-color: var(--color-background-light);
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: var(--shadow-md);
}

.form-group {
  margin-bottom: 1rem;
}

label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
}

input, textarea {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid var(--color-border);
  border-radius: 6px;
  font-size: 1rem;
}

textarea {
  min-height: 100px;
  resize: vertical;
}

.posts {
  display: grid;
  gap: 1.5rem;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
}

.post {
  background-color: var(--color-background-light);
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: var(--shadow-md);
  display: flex;
  flex-direction: column;
  transition: transform 0.2s, box-shadow 0.2s;
}

.post:hover {
  transform: translateY(-5px);
  box-shadow: var(--shadow-lg);
}

.post-content {
  flex: 1;
  margin-bottom: 1rem;
}

.post-meta {
  display: flex;
  justify-content: space-between;
  font-size: 0.9rem;
  color: var(--color-text-muted);
  margin-bottom: 1rem;
}

.post-actions {
  display: flex;
  gap: 0.5rem;
}

.btn-like, .btn-comments {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  font-size: 0.9rem;
}

.icon {
  font-size: 1.2rem;
}

.comments-section {
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid var(--color-border);
}

.comments-list {
  list-style-type: none;
  padding: 0;
}

.comment {
  background-color: var(--color-background);
  padding: 0.75rem;
  margin-bottom: 0.75rem;
  border-radius: 6px;
}

.comment p {
  margin: 0 0 0.25rem 0;
}

.comment small {
  color: var(--color-text-muted);
}

.add-comment-form {
  margin-top: 1rem;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.add-comment-form textarea {
  min-height: 60px;
}

@media (max-width: 768px) {
  .posts {
    grid-template-columns: 1fr;
  }
}
</style>