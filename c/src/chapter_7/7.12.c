#include <stdio.h>
const size_t BUFFER_LEN = 512;

int main(void)
{
	char buffer[BUFFER_LEN];
	char *pS[3] = {NULL};
	char *pbuffer = buffer;
	size_t index = 0;
	int i = 0;

	printf("\nEnter 3 messages that total less than %u characters.", BUFFER_LEN - 2);

	for (i = 0; i < 3; i++) {
		printf("\nEnter %s messages\n", i > 0 ? "another" : "a");
		pS[i] = &buffer[index];
		
		for (; index < BUFFER_LEN; index++) {
			if ((*(pbuffer + index) = getchar()) == '\n') {
				*(pbuffer + index++) = '\0';	
				break;
			}
		}

		if ((index == BUFFER_LEN) && ((*(pbuffer + index - 1) != '\0'))) {
			printf("\nYou ran out of space in the buffer.");
			return 1;	
		}
	}
	
	printf("\nThe strings you entered are : \n\n");
	int j = 0;
	for (i = 0; i < 3; i++) {
		//for (j = 0; *(pS[i] + j)!= '\0'; j++) {
		//	printf("%c", *(pS[i] + j));
		//}
		printf("%s\n", pS[i]);
	}
	printf("The buffer has %d characters unused.\n", BUFFER_LEN - index);
	return 0;
}
