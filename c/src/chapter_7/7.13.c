#include <stdio.h>
#include <stdlib.h>
#include <string.h>

const size_t BUFFER_LEN = 128;
const size_t NUM_P = 2;

int main(void)
{
	char buffer[BUFFER_LEN];
	char *pS[NUM_P] = {NULL};
	char *pbuffer = buffer;
	int i = 0;

	printf("\nYou can enter up to %u message each up to %u characters.", NUM_P, BUFFER_LEN - 1);

	for(i = 0; i < NUM_P; i++) {
		pbuffer = buffer;
		printf("\nEnter %s message , or press Enter to end\n", i > 0 ? "another" : "a");
		
		while ((pbuffer - buffer < BUFFER_LEN - 1) && ((*pbuffer++ = getchar()) != '\n'));
		
		if ((pbuffer - buffer) < 2) {
			break;	
		}
		
		if ((pbuffer - buffer) == BUFFER_LEN && *(pbuffer - 1) != '\n') {
			printf("String too long - maximum %d characters allows.", BUFFER_LEN);
			i--;
			continue;	
		}

		*(pbuffer - 1) = '\0';

		pS[i] = (char*)malloc(pbuffer - buffer);
		if (pS[i] == NULL) {
			printf("\nOut of memory - ending propram.");
			return 1;	
		}

		strcpy(pS[i], buffer);
	}

	while(--i >= 0) {
		printf("\n%s", pS[i]);
		free(pS[i]);
		pS[i] = NULL;	
	}

	return 0;
}
